<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'original_name',
        'src',
        'entity_type_id',
        'entity_id',
    ];
}
