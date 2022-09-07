<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'user_id',
        'entity_type_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }
}
