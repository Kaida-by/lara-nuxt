<?php

namespace App\Http\Interfaces;

use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

interface PosterRepositoryInterface
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection;
    public function showOne(int $id): JsonResponse;
}
