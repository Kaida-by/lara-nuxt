<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Data\ResourceData;

use App\Enums\EntityStatus;
use App\Http\Services\EntityHelper;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class ArticleData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public Collection $categoryIds,
        public int $statusId,
        public string $mainImageUrl,
        public string $created_at,
        public User $user,
    ) {}

    /**
     * @param Article $article
     * @return static
     */
    public static function fromModel(Article $article): self
    {
        return new self(
            id: $article->id,
            title: $article->title,
            description: $article->description,
            categoryIds: $article->categories,
            statusId: $article->status_id,
            mainImageUrl: EntityHelper::getUrlMainImageFromDescription($article->description),
            created_at: $article->created_at,
            user: $article->user,
        );
    }
}
