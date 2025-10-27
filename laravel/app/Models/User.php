<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @method static create(array $array)
 * @method static where(string $string, $token)
 */
class User extends Model {

    use HasFactory;

    protected $fillable = [
        'username',
        'phone',
        'token',
        'expires_at',
        'active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'active' => 'boolean',
    ];

    public static function generateToken(): string {
        return Str::uuid()->toString();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(LuckyHistory::class);
    }

    public function isLinkValid(): bool
    {
        return $this->active && $this->expires_at->isFuture();
    }
}
