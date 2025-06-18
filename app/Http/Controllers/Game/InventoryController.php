<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $inventoryItems = $user->inventory()->with('item')->get();

        return view('game.inventory.index', [
            'items' => $inventoryItems,
        ]);
    }
}
