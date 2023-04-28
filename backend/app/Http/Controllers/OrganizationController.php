<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Data\ResourceData\OrganizationData;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Http\Interfaces\OrganizationInterface;
use App\Models\Organization;
use App\Traits\InteractsWithPhone;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class OrganizationController extends AbstractController implements OrganizationInterface
{
    use InteractsWithPhone;

    public function __construct(public Organization $organization)
    {
    }

    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $count = (int) request('count');
        $organizations = $this->getAll($this->organization, $count, EntityType::Organization->value, EntityStatus::Active->value);

        return OrganizationData::collection($organizations);
    }

    public function showOne(int $id): OrganizationData
    {
        $organization = $this->getOne($this->organization, $id, EntityType::Organization->value);

        return OrganizationData::from($organization);
    }
}
