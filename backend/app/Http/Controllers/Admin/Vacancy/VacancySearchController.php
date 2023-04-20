<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Vacancy;

use App\Data\ResourceData\VacancyData;
use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class VacancySearchController extends Controller
{
    public function search(Request $request): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $vacancies = Vacancy::search($request->get('query'))->get();

        return VacancyData::collection($vacancies);
    }
}
