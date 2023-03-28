<?php

namespace App\Http\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface PosterRepositoryInterface
{
    public function showAll(): AnonymousResourceCollection;
    public function showOne(int $id): JsonResponse;
}
