<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Repositories;

use App\Data\ResourceData\PosterData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\PosterRepositoryInterface;
use App\Http\Services\AbstractEntityHelper;
use App\Models\Poster;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class PosterRepository extends AbstractEntityHelper implements PosterRepositoryInterface
{
    /**
     * @return DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
     */
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        $posters = $this->getAllEntityData(new Poster(), $count, EntityType::Poster->value, EntityStatus::Active->value);

        return PosterData::collection($posters);
    }

    /**
     * @param int $id
     * @return PosterData
     */
    public function showOne(int $id): PosterData
    {
        $poster = $this->getOneEntityData(new Poster(), $id, EntityType::Profile->value);

        return PosterData::from($poster);
    }
}
