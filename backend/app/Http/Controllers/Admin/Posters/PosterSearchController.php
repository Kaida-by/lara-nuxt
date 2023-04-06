<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Posters;

use App\Data\ResourceData\PosterData;
use App\Http\Controllers\Controller;
use App\Models\Poster;
use Illuminate\Http\Request;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class PosterSearchController extends Controller
{
    public function search(Request $request): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $articles = Poster::search($request->get('query'))->get();

        return PosterData::collection($articles);
    }
}
