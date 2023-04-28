<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Organizations;

use App\Data\ResourceData\OrganizationData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityType;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\AbstractAdminEntityHelper;
use App\Http\Services\EntityHelper;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

use function response;

class AdminOrganizationController extends AbstractAdminEntityHelper
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $categoryId = (int) request('categoryId');
        $organizations = $this->getAllEntityData(new Organization(), $categoryId);

        return OrganizationData::collection($organizations);
    }

    public function getCategories(): JsonResponse
    {
        $categoryId = (int) request('categoryId') ?: EntityCategory::Article->value;
        $categories = EntityHelper::getCategories($categoryId, 'article_category');

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    public function edit(int $id): OrganizationData
    {
        $organization = $this->getOneEntityData(new Organization(), $id, EntityType::Organization->value);

        return OrganizationData::from($organization);
    }

    public function approve(int $id, AdminEntityRequest $request): void
    {
        $status = !$request['checked'] ? 2 : 1;
        $this->approveEntity(new Organization(), $id, $status, EntityName::Organization->value);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->deleteEntity(new Organization(), $id, EntityName::Organization->value);
    }

    public function getCountAllOrganizations(): int
    {
        return OrganizationData::collection(Organization::all())->count();
    }
}
