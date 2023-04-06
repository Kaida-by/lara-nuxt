<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Http\Repositories\ArticleRepository;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ArticleController extends Controller
{
    public function __construct(public Article $article, private readonly ArticleRepository $articleRepository)
    {
    }

    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        return $this->articleRepository->showAll();
    }

    public function showOne(Article $article): JsonResponse
    {
        return $this->articleRepository->showOne($article->id);
    }
}
