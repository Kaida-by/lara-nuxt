<?php

namespace App\Traits;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait InteractsWithPhone
{
    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class, 'entity_id');
    }
}
