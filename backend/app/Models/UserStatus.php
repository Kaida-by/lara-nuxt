<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserStatus extends Model
{
    use HasFactory;

    protected $table = 'user_status';

    protected $fillable = [
        'id',
        'code',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'status_id');
    }
}
