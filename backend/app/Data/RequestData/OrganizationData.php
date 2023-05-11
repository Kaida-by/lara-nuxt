<?php

namespace App\Data\RequestData;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Symfony\Contracts\Service\Attribute\Required;

class OrganizationData extends Data
{
    #[Required, StringType, Max(255)]
    public string $title;
    #[Required, StringType, Max(65000)]
    public string $description;
    #[Required, StringType, Max(255)]
    public string $address;
    #[Url]
    public ?string $link;
    #[DataCollectionOf(PhoneData::class)]
    public PhoneData $phone;
    #[DataCollectionOf(ImageData::class)]
    public DataCollection $images;
//    public array $images;
}
