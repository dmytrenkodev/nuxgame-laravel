<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class LuckyHistory extends Model {
    protected $fillable = ['user_id', 'number', 'result', 'win_amount'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
