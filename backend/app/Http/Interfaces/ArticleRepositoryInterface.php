<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Interfaces;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

interface ArticleRepositoryInterface
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection;
    public function showOne(int $id): JsonResponse;
}
