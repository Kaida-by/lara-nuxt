<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\ArticleRepositoryInterface;
use App\Http\Resources\ArticleResource;
use App\Http\Services\EntityHelper;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function showAll(): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', EntityHelper::TYPE_ARTICLE);
            }
        ])
            ->where(['status_id' => EntityHelper::ENTITY_STATUS_ACTIVE])
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(4);

        return ArticleResource::collection($articles);
    }

    public function showOne(Article $article): JsonResponse
    {
        $article = Article::with([
            'user' => function($q) {
                $q->with([
                    'profile' => function ($p) {
                        $p->with([
                            'images' => function ($pi) {
                                $pi->where(['entity_type_id' => 3]);
                            }
                        ]);
                    }
                ]);
            },
            'entityStatus',
            'images' => function($q) {
                $q->where(['entity_type_id' => EntityHelper::TYPE_ARTICLE]);
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $article->id])
            ->get();

        return \response()->json([
            'success' => true,
            'data' => $article
        ]);
    }
}
