<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Http\Repositories\PosterRepository;
use App\Models\Poster;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class PosterController extends Controller
{
    public function __construct(public Poster $poster, private readonly PosterRepository $posterRepository)
    {
    }

    public function showAll(): PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
    {
        return $this->posterRepository->showAll();
    }

    public function showOne(Poster $poster): JsonResponse
    {
        return $this->posterRepository->showOne($poster->id);
    }
}
