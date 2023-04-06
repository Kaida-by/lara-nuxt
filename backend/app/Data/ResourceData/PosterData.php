<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Data\ResourceData;

use App\Enums\EntityStatus;
use App\Http\Services\EntityHelper;
use App\Models\Poster;
use App\Models\User;
use Spatie\LaravelData\Data;

class PosterData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $date,
        public float $price,
        public int $statusId,
        public string $mainImageUrl,
        public string $created_at,
        public User $user,
    ) {}

    /**
     * @param Poster $poster
     * @return static
     */
    public static function fromModel(Poster $poster): self
    {
        return new self(
            id: $poster->id,
            title: $poster->title,
            description: $poster->description,
            date: $poster->date,
            price: $poster->price,
            statusId: $poster->status_id,
            mainImageUrl: EntityHelper::getUrlMainImageFromDescription($poster->description),
            created_at: $poster->created_at,
            user: $poster->user,
        );
    }
}
