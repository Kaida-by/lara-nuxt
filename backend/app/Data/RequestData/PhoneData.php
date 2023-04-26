<?php

namespace App\Data\RequestData;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class PhoneData extends Data
{
    public ?int $id;
    #[Required, StringType, Min(19)]
    public string $number;
}
