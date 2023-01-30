<?php

namespace App\Http\Interfaces;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface ArticleRepositoryInterface
{
    public function showAll(): AnonymousResourceCollection;
    public function showOne(Article $article): JsonResponse;
}
