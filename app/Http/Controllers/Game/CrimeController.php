<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Crime;
use App\Models\CrimeLog;
use App\Services\CrimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CrimeController extends Controller
{
    public function __construct(protected CrimeService $crimeService)
    {
    }

    public function index(): View
    {
        $user = Auth::user();
        $crimesData = $this->crimeService->getCrimeDataForUser($user);

        return view('game.crimes.index', [
            'crimes' => $crimesData,
            'user' => $user,
        ]);
    }

    public function attempt(Crime $crime)
    {
        $user = Auth::user();
        $outcome = $this->crimeService->attemptCrime($user, $crime);

        if ($outcome->wasSuccessful) {
            return redirect()->route('game.crimes.index')->with('success', $outcome->message);
        } else {
            return redirect()->route('game.crimes.index')->with('error', $outcome->message);
        }
    }
}
