<?php

namespace App\Services;

use App\Enums\RefineFailureOutcomeEnum;
use App\Models\TierUpgradeRule;
use App\Models\User;
use App\Models\UserItem;
use Illuminate\Support\Facades\DB;

class RefiningService
{

    public function __construct(protected InventoryService $inventoryService)
    {
    }
    public function attemptRefine(User $user, UserItem $equipmentToRefine): array
    {

        $currentTier = $equipmentToRefine->tier;
        $rule = TierUpgradeRule::query()->where('from_tier', $currentTier)->first();

        if ($equipmentToRefine->item->item_type !== \App\Enums\ItemTypeEnum::EQUIPMENT) {
            return ['success' => false, 'message' => 'Apenas equipamentos podem ser refinados.'];
        }
        if (!$rule) {
            return ['success' => false, 'message' => 'Este equipamento já atingiu o tier máximo ou não há regra para o próximo nível.'];
        }

        $requiredItem = $rule->requiredItem;

        if ($user->money < $rule->required_money) {
            return ['success' => false, 'message' => "Dinheiro insuficiente. Custo: {$rule->required_money}."];
        }

        $materialInInventory = $user->inventory()
            ->where('item_id', $rule->required_item_id)
            ->first();

        if (!$materialInInventory || $materialInInventory->quantity < $rule->required_item_quantity) {
            return ['success' => false, 'message' => "Material insuficiente. Necessário: {$rule->required_item_quantity}x {$requiredItem->name}."];
        }


        $result = DB::transaction(function () use ($user, $equipmentToRefine, $rule, $materialInInventory) {
            // Consome os recursos
            $user->money -= $rule->required_money;
            $user->save();
            $this->inventoryService->removeItem($user, $materialInInventory, $rule->required_item_quantity);

            // Determina o sucesso
            $isSuccessful = rand(1, 100) <= $rule->success_chance;

            if ($isSuccessful) {
                $equipmentToRefine->increment('tier');
                $newTier = $equipmentToRefine->tier;
                return ['success' => true, 'message' => "Sucesso! Seu equipamento agora é Tier $newTier."];
            }

            // Se falhou, aplica a consequência
            $this->handleFailure($equipmentToRefine, $rule);

            return ['success' => false, 'message' => "Falha! O refinamento não funcionou e os recursos foram consumidos."];
        });

        return $result;
    }

    /**
     * Aplica a consequência de uma falha de refinamento baseada na regra.
     */
    private function handleFailure(UserItem $equipment, TierUpgradeRule $rule): void
    {
        switch ($rule->failure_outcome) {
            case RefineFailureOutcomeEnum::DOWNGRADE_TIER:
                if ($equipment->tier > 0) {
                    $equipment->decrement('tier');
                }
                break;
            case RefineFailureOutcomeEnum::DESTROY_ITEM:
                $equipment->delete();
                break;
            case RefineFailureOutcomeEnum::MAINTAIN_TIER:
                // Não faz nada, o item é mantido como está.
                break;
        }
    }

    /**
     * Busca a chance de sucesso para um determinado tier no banco de dados.
     */
    public function getSuccessChanceForTier(int $currentTier): int
    {
        // Busca a regra para o tier atual e retorna a chance de sucesso, ou 0 se não houver regra.
        $rule = TierUpgradeRule::query()->where('from_tier', $currentTier)->first();
        return $rule->success_chance ?? 0;
    }
}
