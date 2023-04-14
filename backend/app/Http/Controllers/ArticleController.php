<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\ArticleData;
use App\Http\Repositories\ArticleRepository;
use App\Models\Article;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ArticleController extends Controller
{
    public function __construct(public Article $article, private readonly ArticleRepository $articleRepository)
    {
    }

    /**
     * @return DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
     */
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        return $this->articleRepository->showAll();
    }

    /**
     * @param Article $article
     * @return ArticleData
     */
    public function showOne(Article $article): ArticleData
    {
        return $this->articleRepository->showOne($article->id);
    }
}
