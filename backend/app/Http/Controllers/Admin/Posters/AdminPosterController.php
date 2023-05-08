<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Posters;

use App\Data\ResourceData\PosterData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityType;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\AbstractAdminEntityHelper;
use App\Http\Services\EntityHelper;
use App\Models\Poster;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

use function response;

class AdminPosterController extends AbstractAdminEntityHelper
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $categoryId = (int) request('categoryId');
        $posters = $this->getAllEntityData(new Poster(), $categoryId);

        return PosterData::collection($posters);
    }

    public function getCategories(): JsonResponse
    {
        $categoryId = (int) request('categoryId') ?: EntityCategory::Article->value;
        $categories = EntityHelper::getCategories($categoryId, 'poster_category');

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    public function edit(int $id): PosterData
    {
        $poster = $this->getOneEntityData(new Poster(), $id, EntityType::Poster->value);

        return PosterData::from($poster);
    }

    public function approve(int $id, AdminEntityRequest $request): void
    {
        $status = !$request['checked'] ? 2 : 1;
        $this->approveEntity(new Poster(), $id, $status, EntityName::Poster-> value);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->deleteEntity(new Poster(), $id, EntityName::Poster-> value);
    }

    public function getCountAllPosters(): int
    {
        return PosterData::collection(Poster::all())->count();
    }
}
