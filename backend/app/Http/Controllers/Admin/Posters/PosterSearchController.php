<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Posters;

use App\Http\Controllers\Controller;
use App\Http\Resources\PosterResource;
use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PosterSearchController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        $articles = Poster::search($request->get('query'))->get();

        return PosterResource::collection($articles);
    }
}
