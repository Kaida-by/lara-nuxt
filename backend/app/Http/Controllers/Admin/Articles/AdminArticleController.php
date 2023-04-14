<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Articles;

use App\Data\ResourceData\ArticleData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\AbstractAdminEntityHelper;
use App\Http\Services\EntityHelper;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Throwable;

use function response;

class AdminArticleController extends AbstractAdminEntityHelper
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $categoryId = (int) request('categoryId');
        $articles = $this->getAllEntityData(new Article(), $categoryId);

        return ArticleData::collection($articles);
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

    public function edit(int $id): ArticleData
    {
        $article = $this->getOneEntityData(new Article(), $id);

        return ArticleData::from($article);
    }

    public function approve(int $id, AdminEntityRequest $request): void
    {
        $status = !$request['checked'] ? 2 : 1;
        $this->approveEntity(new Article(), $id, $status, EntityName::Article-> value);
    }

    public function delete(int $id): JsonResponse
    {
        return $this->deleteEntity(new Article(), $id, EntityName::Article-> value);
    }

    public function getCountAllArticles(): int
    {
        return ArticleData::collection(Article::all())->count();
    }
}
