<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ArticleRepository;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController
{
    public function __construct(public Article $article, private ArticleRepository $articleRepository)
    {
    }

    public function showAll(): AnonymousResourceCollection
    {
        return $this->articleRepository->showAll();
    }

    public function showOne(Article $article): JsonResponse
    {
        return $this->articleRepository->showOne($article->id);
    }
}
