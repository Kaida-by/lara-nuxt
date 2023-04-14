<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Repositories;

use App\Data\ResourceData\ArticleData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\ArticleRepositoryInterface;
use App\Http\Services\AbstractEntityHelper;
use App\Models\Article;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ArticleRepository extends AbstractEntityHelper implements ArticleRepositoryInterface
{
    /**
     * @return DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
     */
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        $articles = $this->getAllEntityData(new Article(), $count, EntityType::Article->value, EntityStatus::Active->value);

        return ArticleData::collection($articles);
    }

    /**
     * @param int $id
     * @return ArticleData
     */
    public function showOne(int $id): ArticleData
    {
        $article = $this->getOneEntityData(new Article(), $id, EntityType::Article->value);

        return ArticleData::from($article);
    }
}
