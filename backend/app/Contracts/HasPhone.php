<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface HasPhone
{
    public function phone(): HasOne;
}
