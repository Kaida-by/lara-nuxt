<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Repositories;

use App\Http\Interfaces\PosterRepositoryInterface;
use App\Http\Resources\PosterResource;
use App\Http\Services\EntityHelper;
use App\Models\Poster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PosterRepository implements PosterRepositoryInterface
{
    public function showAll(): AnonymousResourceCollection
    {
        $count = (int) request('count');

        if ($count && $count <= 24) {
            $posters = Poster::with([
                'images' => function($q) {
                    $q->where('entity_type_id', EntityHelper::TYPE_POSTERS);
                }
            ])
                ->where(['status_id' => EntityHelper::ENTITY_STATUS_ACTIVE])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate($count);
        } else {
            $posters = Poster::with([
                'images' => function($q) {
                    $q->where('entity_type_id', EntityHelper::TYPE_POSTERS);
                }
            ])
                ->where(['status_id' => EntityHelper::ENTITY_STATUS_ACTIVE])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate(4);
        }

        return PosterResource::collection($posters);
    }

    public function showOne(int $id): JsonResponse
    {
        $poster = Poster::with([
            'user' => function($q) {
                $q->with([
                    'profile' => function ($q) {
                        $q->with([
                            'images' => function ($q) {
                                $q->where(['entity_type_id' => 3]);
                            }
                        ]);
                    }
                ]);
            },
            'entityStatus',
            'images' => function($q) {
                $q->where(['entity_type_id' => EntityHelper::TYPE_POSTERS]);
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $id])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $poster
        ]);
    }
}
