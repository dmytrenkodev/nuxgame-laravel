<?php

namespace App\Services;

use App\Models\LuckyHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class LuckyService
{
    private const WIN_RULES = [
        ['min' => 900, 'percent' => 0.7],
        ['min' => 600, 'percent' => 0.5],
        ['min' => 300, 'percent' => 0.3],
        ['min' => 0,   'percent' => 0.1],
    ];
    private const MAX_LUCKY_NUMBER = 1000;

    /**
     * @param string $token
     * @return User
     */
    public function findUserAndValidateLink(string $token): User
    {
        $user = User::where('token', $token)->firstOrFail();

        if (!$user->isLinkValid()) {
            abort(403, 'Link expired or deactivated');
        }

        return $user;
    }

    /**
     * @param User $user
     * @return string
     */
    public function regenerateToken(User $user): string
    {
        $user->token = Str::uuid()->toString();
        $user->expires_at = now()->addDays(7);
        $user->save();

        return $user->token;
    }

    /**
     * @param User $user
     * @return void
     */
    public function deactivateUser(User $user): void
    {
        $user->active = false;
        $user->save();
    }

    /**
     * @param User $user
     * @return array
     */
    public function executeLuckyDraw(User $user): array
    {
        $number = rand(1, self::MAX_LUCKY_NUMBER);
        $result = $number % 2 === 0 ? 'Win' : 'Lose';
        $winAmount = 0.0;

        if ($result === 'Win') {
            foreach (self::WIN_RULES as $rule) {
                if ($number >= $rule['min']) {
                    $winAmount = $number * $rule['percent'];
                    break;
                }
            }
        }

        LuckyHistory::create([
            'user_id' => $user->id,
            'number' => $number,
            'result' => $result,
            'win_amount' => round($winAmount, 2),
        ]);

        return [
            'number' => $number,
            'result' => $result,
            'win_amount' => $winAmount,
        ];
    }

    /**
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public function getHistory(User $user, int $limit = 3): Collection
    {
        return $user->histories()->latest()->take($limit)->get();
    }
}
