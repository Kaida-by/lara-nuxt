<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Models;

use App\Contracts\HasPhone;
use App\Http\Interfaces\EntityInterface;
use App\Traits\InteractsWithPhone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravel\Scout\Searchable;

class Vacancy extends Model implements EntityInterface, HasPhone
{
    use HasFactory, Searchable, InteractsWithPhone;

    protected $fillable = [
        'title',
        'description',
        'author_id',
        'entity_type_id',
        'status_id',
    ];

    protected $dates = [
        'created_at'
    ];

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Profile::class,
            'id',
            'id',
            'author_id',
            'user_id'
        );
    }

    public function entityStatus(): HasOne
    {
        return $this->hasOne(EntityStatus::class, 'id', 'status_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'id', 'author_id');
    }
}
