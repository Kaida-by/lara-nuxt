<?php

namespace App\Data\RequestData;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class ArticleData extends Data
{
    #[Required, StringType, Max(255)]
    public string $title;
    #[Required, StringType, Max(65000)]
    public string $description;
    #[Required]
    public array $categoryIds;
}
