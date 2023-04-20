<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\CV;

use App\Data\ResourceData\CVData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityType;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\AbstractAdminEntityHelper;
use App\Http\Services\EntityHelper;
use App\Models\CV;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

use function response;

class AdminCVController extends AbstractAdminEntityHelper
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $categoryId = (int) request('categoryId');
        $cvs = $this->getAllEntityData(new CV(), $categoryId);

        return CVData::collection($cvs);
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

    public function edit(int $id): CVData
    {
        $cv = $this->getOneEntityData(new CV(), $id, EntityType::CV->value);

        return CvData::from($cv);
    }

    public function approve(int $id, AdminEntityRequest $request): void
    {
        $status = !$request['checked'] ? 2 : 1;
        $this->approveEntity(new CV(), $id, $status, EntityName::CV-> value);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->deleteEntity(new CV(), $id, EntityName::CV-> value);
    }

    public function getCountAllCVs(): int
    {
        return CVData::collection(CV::all())->count();
    }
}
