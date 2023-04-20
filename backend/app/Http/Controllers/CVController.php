<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\CVData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\CVInterface;
use App\Models\CV;
use App\Traits\InteractsWithPhone;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class CVController extends AbstractController implements CVInterface
{
    use InteractsWithPhone;

    public function __construct(public CV $cv)
    {
    }

    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        $cvs = $this->getAll($this->cv, $count, EntityType::CV->value, EntityStatus::Active->value);

        return CVData::collection($cvs);
    }

    public function showOne(int $id): CVData
    {
        $cv = $this->getOne($this->cv, $id, EntityType::CV->value);

        return CVData::from($cv);
    }
}
