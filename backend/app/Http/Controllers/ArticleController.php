<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\ArticleData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\ArticleInterface;
use App\Models\Article;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ArticleController extends AbstractController implements ArticleInterface
{
    public function __construct(public Article $article)
    {
    }

    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        $articles = $this->getAll($this->article, $count, EntityType::Article->value, EntityStatus::Active->value);

        return ArticleData::collection($articles);
    }

    public function showOne(int $id): ArticleData
    {
        $article = $this->getOne($this->article, $id, EntityType::Article->value);

        return ArticleData::from($article);
    }
}
