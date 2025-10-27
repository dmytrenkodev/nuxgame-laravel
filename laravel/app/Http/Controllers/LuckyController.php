<?php

namespace App\Http\Controllers;

use App\Models\LuckyHistory;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LuckyController extends Controller
{
    private const WIN_RULES = [
        ['min' => 901, 'percent' => 0.7],
        ['min' => 601, 'percent' => 0.5],
        ['min' => 301, 'percent' => 0.3],
        ['min' => 0,   'percent' => 0.1],
    ];

    private const MAX_LUCKY_NUMBER = 1000;

    public function show($token): Factory|View
    {
        $user = User::where('token', $token)->firstOrFail();

        if (!$user->isLinkValid()) {
            abort(403, 'Link expired or deactivated');
        }

        return view('lucky', compact('user'));
    }

    public function regenerate($token): RedirectResponse
    {
        $user = User::where('token', $token)->firstOrFail();
        $user->token = User::generateToken();
        $user->expires_at = now()->addDays(7);
        $user->save();

        return redirect()->route('lucky.page', ['token' => $user->token]);
    }

    public function deactivate($token): RedirectResponse
    {
        $user = User::where('token', $token)->firstOrFail();
        $user->active = false;
        $user->save();

        return redirect()->route('register.form')->with('status', 'Link deactivated');
    }

    public function imFeelingLucky($token)
    {
        $user = User::where('token', $token)->firstOrFail();
        if (!$user->isLinkValid()) abort(403, 'Link expired or deactivated');

        $number = rand(1, self::MAX_LUCKY_NUMBER);
        $result = $number % 2 === 0 ? 'Win' : 'Lose';

        $win_amount = 0;

        if ($result === 'Win') {
            foreach (self::WIN_RULES as $rule) {
                if ($number >= $rule['min']) {
                    $win_amount = $number * $rule['percent'];
                    break;
                }
            }
        }

        LuckyHistory::create([
            'user_id' => $user->id,
            'number' => $number,
            'result' => $result,
            'win_amount' => $win_amount,
        ]);

        return redirect()->route('lucky.page', ['token' => $user->token])
            ->with('lucky', compact('number', 'result', 'win_amount'));
    }

    public function history($token): Factory|View
    {
        $user = User::where('token', $token)->firstOrFail();
        $histories = $user->histories()->latest()->take(3)->get();

        return view('history', compact('user', 'histories'));
    }
}
