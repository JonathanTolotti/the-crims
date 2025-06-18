<?php

namespace App\Services;

use App\Enums\EquipmentSlotEnum;
use App\Enums\ItemTypeEnum;
use App\Models\User;
use App\Models\UserItem;

class EquipmentService
{
    /**
     * Equipa um item
     *
     * @param User $user
     * @param UserItem $userItemToEquip
     * @return array
     */
    public function equipItem(User $user, UserItem $userItemToEquip): array
    {
        $item = $userItemToEquip->item;

        // Validação
        if ($item->item_type !== ItemTypeEnum::EQUIPMENT) {
            return ['success' => false, 'message' => 'Este item não é um equipamento.'];
        }
        if ($user->levelDefinition->id < $item->required_level_id) {
            return ['success' => false, 'message' => 'Você não tem o nível necessário para equipar este item.'];
        }
        if ($userItemToEquip->is_equipped) {
            return ['success' => false, 'message' => 'Este item já está equipado.'];
        }

        // Desequipa qualquer item que esteja no mesmo slot
        $this->unequipItemInSlot($user, $item->equipment_slot);

        // Equipa o novo item
        $userItemToEquip->is_equipped = true;
        $userItemToEquip->save();

        return ['success' => true, 'message' => "{$item->name} equipado."];
    }

    /**
     * Desequipa um item
     *
     * @param User $user
     * @param UserItem $userItemToUnequip
     * @return array
     */
    public function unequipItem(User $user, UserItem $userItemToUnequip): array
    {
        if (!$userItemToUnequip->is_equipped) {
            return ['success' => false, 'message' => 'Este item não está equipado.'];
        }

        $userItemToUnequip->is_equipped = false;
        $userItemToUnequip->save();

        return ['success' => true, 'message' => "{$userItemToUnequip->item->name} desequipado."];
    }

    // Método auxiliar para desequipar itens de um slot específico
    private function unequipItemInSlot(User $user, ?EquipmentSlotEnum $slot): void
    {
        if (!$slot) return;

        $user->inventory()
            ->where('is_equipped', true)
            ->whereHas('item', function ($query) use ($slot) {
                $query->where('equipment_slot', $slot->value);
            })
            ->update(['is_equipped' => false]);
    }
}
