<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\TierUpgradeRule;
use App\Models\UserItem;
use App\Services\RefiningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RefiningController extends Controller
{
    public function __construct(protected RefiningService $refiningService)
    {
    }

    public function index(): View
    {
        $user = Auth::user();

        $ownedRefiningMaterials = $user->inventory()
            ->with('item')
            ->whereHas('item', fn($query) => $query->where('item_type', \App\Enums\ItemTypeEnum::REFINING_MATERIAL))
            ->get();

        $equipmentInInventory = $user->inventory()
            ->with('item')
            ->whereHas('item', fn($query) => $query->where('item_type', 'equipment'))
            ->get();

        $tiers = $equipmentInInventory->pluck('tier')->unique();
        $rules = TierUpgradeRule::query()->whereIn('from_tier', $tiers)->with('requiredItem')->get()->keyBy('from_tier');

        $requiredItemIds = $rules->pluck('required_item_id')->unique();
        $materialsInInventory = $user->inventory()
            ->whereIn('item_id', $requiredItemIds)
            ->get()
            ->keyBy('item_id');

        $equipmentData = $equipmentInInventory->map(function ($userItem) use ($rules, $user, $ownedRefiningMaterials) {
            $rule = $rules->get($userItem->tier);

            if (!$rule) {
                $reasonsToBlock[] = 'Tier máximo alcançado.';
            } else {
                $materialRequired = $rule->requiredItem;
                $materialOwned = $ownedRefiningMaterials->where('item_id', $materialRequired->id)->first();

                if (!$materialOwned || $materialOwned->quantity < $rule->required_item_quantity) {
                    $reasonsToBlock[] = "Material insuficiente ({$rule->required_item_quantity}x {$materialRequired->name}).";
                }
                if ($user->money < $rule->required_money) {
                    $needMoneyFormatted = number_format($rule->required_money);
                    $reasonsToBlock[] = "Dinheiro insuficiente R$ ({$needMoneyFormatted}).";
                }
            }

            return (object) [
                'userItem' => $userItem,
                'rule' => $rule,
                'canAttempt' => empty($reasonsToBlock),
                'reasonsToBlock' => $reasonsToBlock ?? [],
            ];
        });

        return view('game.refinery.index', [
            'equipmentData' => $equipmentData,
            'ownedRefiningMaterials' => $ownedRefiningMaterials,
        ]);
    }

    public function refine(UserItem $userItem)
    {
        // Garante que o jogador só pode refinar seus próprios itens
        abort_if($userItem->user_id !== Auth::id(), 403);

        $result = $this->refiningService->attemptRefine(Auth::user(), $userItem);

        return redirect()->route('game.refinery.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
