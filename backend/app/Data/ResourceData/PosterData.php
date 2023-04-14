<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Data\ResourceData;

use App\Http\Services\EntityHelper;
use App\Models\Poster;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class PosterData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public Collection $categoryIds,
        public string $date,
        public float $price,
        public $status,
        public int $statusId,
        public string $mainImageUrl,
        public string $created_at,
        public string $updated_at,
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
            categoryIds: $poster->categories,
            date: $poster->date,
            price: $poster->price,
            status: $poster->entityStatus,
            statusId: $poster->status_id,
            mainImageUrl: EntityHelper::getUrlMainImageFromDescription($poster->description),
            created_at: $poster->created_at,
            updated_at: $poster->updated_at,
            user: $poster->user,
        );
    }
}
