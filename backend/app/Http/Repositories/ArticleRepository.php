<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Repositories;

use App\Data\ResourceData\ArticleData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use function response;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        if ($count && $count <= 24) {
            $articles = Article::with([
                'images' => function($q) {
                    $q->where('entity_type_id', EntityType::Article->value);
                }
            ])
                ->where(['status_id' => EntityStatus::Active->value])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate($count);
        } else {
            $articles = Article::with([
                'images' => function($q) {
                    $q->where('entity_type_id', EntityType::Article->value);
                }
            ])
                ->where(['status_id' => EntityStatus::Active->value])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate(4);
        }

        return ArticleData::collection($articles);
    }

    public function showOne(int $id): JsonResponse
    {
        $article = Article::with([
            'user' => function($q) {
                $q->with([
                    'profile' => function ($q) {
                        $q->with([
                            'images' => function ($q) {
                                $q->where(['entity_type_id' => EntityType::Profile->value]);
                            }
                        ]);
                    }
                ]);
            },
            'entityStatus',
            'images' => function($q) {
                $q->where(['entity_type_id' => EntityType::Article->value]);
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $id])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => ArticleData::from($article)
        ]);
    }
}
