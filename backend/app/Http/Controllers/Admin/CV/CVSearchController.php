<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\CV;

use App\Data\ResourceData\CVData;
use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class CVSearchController extends Controller
{
    public function search(Request $request): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $cvs = CV::search($request->get('query'))->get();

        return CVData::collection($cvs);
    }
}
