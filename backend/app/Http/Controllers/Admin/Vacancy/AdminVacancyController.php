<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Vacancy;

use App\Data\ResourceData\VacancyData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityType;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\AbstractAdminEntityHelper;
use App\Http\Services\EntityHelper;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

use function response;

class AdminVacancyController extends AbstractAdminEntityHelper
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $categoryId = (int) request('categoryId');
        $vacancies = $this->getAllEntityData(new Vacancy(), $categoryId);

        return VacancyData::collection($vacancies);
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

    public function edit(int $id): VacancyData
    {
        $vacancy = $this->getOneEntityData(new Vacancy(), $id, EntityType::Vacancy->value);

        return VacancyData::from($vacancy);
    }

    public function approve(int $id, AdminEntityRequest $request): void
    {
        $status = !$request['checked'] ? 2 : 1;
        $this->approveEntity(new Vacancy(), $id, $status, EntityName::Vacancy-> value);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->deleteEntity(new Vacancy(), $id, EntityName::Vacancy-> value);
    }

    public function getCountAllVacancies(): int
    {
        return VacancyData::collection(Vacancy::all())->count();
    }
}
