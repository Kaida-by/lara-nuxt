<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Repositories;

use App\Data\ResourceData\PosterData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\PosterRepositoryInterface;
use App\Models\Poster;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class PosterRepository implements PosterRepositoryInterface
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');

        if ($count && $count <= 24) {
            $posters = Poster::with([
                'images' => function($q) {
                    $q->where('entity_type_id', EntityType::Poster->value);
                }
            ])
                ->where(['status_id' => EntityStatus::Active->value])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate($count);
        } else {
            $posters = Poster::with([
                'images' => function($q) {
                    $q->where('entity_type_id', EntityType::Poster->value);
                }
            ])
                ->where(['status_id' => EntityStatus::Active->value])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate(4);
        }

        return PosterData::collection($posters);
    }

    public function showOne(int $id): JsonResponse
    {
        $poster = Poster::with([
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
                $q->where(['entity_type_id' => EntityType::Poster->value]);
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $id])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => PosterData::from($poster)
        ]);
    }
}
