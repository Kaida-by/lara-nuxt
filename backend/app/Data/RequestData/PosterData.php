<?php

namespace App\Data\RequestData;

use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class PosterData extends Data
{
    #[Required, StringType, Max(255)]
    public string $title;
    #[Required, StringType, Max(65000)]
    public string $description;
    #[Required]
    public string $date;
    #[Required, Numeric, Between(0, 1000000.99)]
    public float $price;
    #[Required]
    public array $categories;
}
