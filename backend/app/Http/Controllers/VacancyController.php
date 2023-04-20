<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\VacancyData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\CVInterface;
use App\Models\Vacancy;
use App\Traits\InteractsWithPhone;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class VacancyController extends AbstractController implements CVInterface
{
    use InteractsWithPhone;

    public function __construct(public Vacancy $vacancy)
    {
    }

    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        $cvs = $this->getAll($this->vacancy, $count, EntityType::Vacancy->value, EntityStatus::Active->value);

        return VacancyData::collection($cvs);
    }

    public function showOne(int $id): VacancyData
    {
        $cv = $this->getOne($this->vacancy, $id, EntityType::Vacancy->value);

        return VacancyData::from($cv);
    }
}
