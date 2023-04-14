<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\PosterData;
use App\Http\Repositories\PosterRepository;
use App\Models\Poster;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class PosterController extends Controller
{
    public function __construct(public Poster $poster, private readonly PosterRepository $posterRepository)
    {
    }

    /**
     * @return PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
     */
    public function showAll(): PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
    {
        return $this->posterRepository->showAll();
    }

    /**
     * @param Poster $poster
     * @return PosterData
     */
    public function showOne(Poster $poster): PosterData
    {
        return $this->posterRepository->showOne($poster->id);
    }
}
