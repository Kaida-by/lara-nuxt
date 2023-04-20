<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\PosterData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\PosterInterface;
use App\Models\Poster;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class PosterController extends AbstractController implements PosterInterface
{
    public function __construct(public Poster $poster)
    {
    }

    public function showAll(): PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
    {
        $count = (int) request('count');
        $posters = $this->getAll($this->poster, $count, EntityType::Poster->value, EntityStatus::Active->value);

        return PosterData::collection($posters);
    }

    public function showOne(int $id): PosterData
    {
        $poster = $this->getOne($this->poster, $id, EntityType::Profile->value);

        return PosterData::from($poster);
    }
}
