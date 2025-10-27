<?php

namespace App\Http\Controllers;

use App\Services\LuckyService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LuckyController extends Controller
{
    protected LuckyService $luckyService;

    /**
     * @param LuckyService $luckyService
     */
    public function __construct(LuckyService $luckyService)
    {
        $this->luckyService = $luckyService;
    }

    /**
     * @param string $token
     * @return Factory|View
     */
    public function show(string $token): Factory|View
    {
        $user = $this->luckyService->findUserAndValidateLink($token);

        return view('lucky', compact('user'));
    }

    /**
     * @param string $token
     * @return RedirectResponse
     */
    public function regenerate(string $token): RedirectResponse
    {
        $user = $this->luckyService->findUserAndValidateLink($token);

        $newToken = $this->luckyService->regenerateToken($user);

        return redirect()->route('lucky.page', ['token' => $newToken]);
    }

    /**
     * @param string $token
     * @return RedirectResponse
     */
    public function deactivate(string $token): RedirectResponse
    {
        $user = $this->luckyService->findUserAndValidateLink($token);

        $this->luckyService->deactivateUser($user);

        return redirect()->route('register.form')->with('status', 'Link deactivated');
    }

    /**
     * @param string $token
     * @return RedirectResponse
     */
    public function imFeelingLucky(string $token): RedirectResponse
    {
        $user = $this->luckyService->findUserAndValidateLink($token);

        $resultData = $this->luckyService->executeLuckyDraw($user);

        return redirect()->route('lucky.page', ['token' => $user->token])
            ->with('lucky', $resultData);
    }

    /**
     * @param string $token
     * @return Factory|View
     */
    public function history(string $token): Factory|View
    {
        $user = $this->luckyService->findUserAndValidateLink($token);

        $histories = $this->luckyService->getHistory($user, 3);

        return view('history', compact('user', 'histories'));
    }
}
