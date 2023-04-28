<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Organizations;

use App\Data\ResourceData\OrganizationData;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class OrganizationSearchController extends Controller
{
    public function search(Request $request): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $cvs = Organization::search($request->get('query'))->get();

        return OrganizationData::collection($cvs);
    }
}
