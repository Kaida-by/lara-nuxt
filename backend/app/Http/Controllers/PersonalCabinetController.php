<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Services\UploadImagesService;
use App\Models\Article;
use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PersonalCabinetController
{
    protected Article $article;
    protected User $user;

    const ENTITY_TYPE = 2;
    const CATEGORY = 2;

    public function __construct(Article $article, User $user)
    {
        $this->article = $article;
        $this->user = $user;
    }

    public function getMyArticles(Request $request): AnonymousResourceCollection
    {
        $userId = $request->user()->id;
        $profileId = Profile::select('id')->where(['id' => $userId])->limit(1)->get();

        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', self::ENTITY_TYPE);
            }
        ])
            ->where(['status_id' => Article::ENTITY_STATUS_ACTIVE])
            ->where(['author_id' => $profileId[0]->id])
            ->simplePaginate(10);

        return ArticleResource::collection($articles);
    }

    public function store(ArticleRequest $request)
    {
        $validatedData = $request->validated();

        $this->article->title = $validatedData['title'];
        $this->article->description = $validatedData['description'];
        $this->article->author_id = Auth::id();
        $this->article->entity_type_id = self::ENTITY_TYPE;
        $this->article->category_id = self::CATEGORY;
        $this->article->status_id = Article::ENTITY_STATUS_UNDER_MODERATION;

        $lastId = Article::latest()->first()->id ?? 0;
        $currentId = $lastId + 1;

//        UploadImagesService::save($request->files->get('files'), self::ENTITY_TYPE, $currentId);

        DB::beginTransaction();

        try {
            $this->article->save();

            $cat = [1,4,5];
            $c = Category::find($cat);
            $this->article->categories()->attach($c);

            DB::commit();

            return \response()->json([
                'success' => true,
                'article' => $this->article
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();

            return \response()->json([
                'success' => false,
                'errors' => [
                    'text' => [
                        $exception
                    ]
                ]
            ], 500);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $article = Article::with([
            'user' => function($q) {
                $q->with(['profile']);
            },
            'entityStatus',
            'images'
        ])
            ->where(['id' => $id])
            ->get();

        return \response()->json([
            'success' => true,
            'data' => $article
        ], 200);
    }

    public function update(ArticleRequest $request, $id): JsonResponse
    {
        $article = Article::find($id);
        $article->title = $request['title'];
        $article->description = $request['description'];

//        UploadImagesService::update(self::ENTITY_TYPE, $id, $request['is_main_image']);

        $article->update();

        return \response()->json([
            'success' => true,
            'data' => $article
        ], 200);
    }
}
