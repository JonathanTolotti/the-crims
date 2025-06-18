<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\UserItem;
use App\Services\EquipmentService;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InventoryController extends Controller
{

    public function __construct(
        protected InventoryService $inventoryService,
        protected EquipmentService $equipmentService
    ) {}
    public function index(): View
    {
        $user = Auth::user();

        $typeOrder = "CASE
            WHEN items.item_type = 'equipment' THEN 1
            WHEN items.item_type = 'consumable' THEN 2
            WHEN items.item_type = 'refining_material' THEN 3
            ELSE 4
        END";

        $inventoryItems = $user->inventory()
            ->with('item')
            ->join('items', 'user_items.item_id', '=', 'items.id')
            ->orderByRaw($typeOrder) // 1. Ordena pela nossa prioridade customizada
            ->orderBy('items.name')   // 2. Como desempate, ordena pelo nome do item
            ->select('user_items.*')  // Evita conflitos de colunas com mesmo nome (ex: id)
            ->get();

        return view('game.inventory.index', ['items' => $inventoryItems]);
    }

    public function use(UserItem $userItem): RedirectResponse
    {
        abort_if($userItem->user_id !== Auth::id(), 403);

        $result = $this->inventoryService->useItem(Auth::user(), $userItem);

        return redirect()->route('game.inventory.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function equip(UserItem $userItem): RedirectResponse
    {
        abort_if($userItem->user_id !== Auth::id(), 403);

        $result = $this->equipmentService->equipItem(Auth::user(), $userItem);

        return redirect()->route('game.inventory.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function unequip(UserItem $userItem): RedirectResponse
    {
        abort_if($userItem->user_id !== Auth::id(), 403);

        $result = $this->equipmentService->unequipItem(Auth::user(), $userItem);

        return redirect()->route('game.inventory.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
