<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_role';

    protected $fillable = [
        'id',
        'code',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'role_id');
    }
}
