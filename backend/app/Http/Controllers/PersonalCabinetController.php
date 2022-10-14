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
    const ENTITY_TYPE = 2;
    const CATEGORY = 2;

    protected Article $article;
    protected $profileId;
    protected $auth;

    public function __construct(Article $article)
    {
        $this->article = $article;
        $this->profileId = Profile::select('id')->where(['user_id' => 1])->limit(1)->get()[0]->id;
    }

    public function getMyArticles(Request $request): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', self::ENTITY_TYPE);
            }
        ])
            ->where(['status_id' => Article::ENTITY_STATUS_ACTIVE])
            ->where(['author_id' => $this->profileId])
            ->simplePaginate(10);

        return ArticleResource::collection($articles);
    }

    public function store(ArticleRequest $request)
    {
        $validatedData = $request->validated();

        $this->article->title = $validatedData['title'];
        $this->article->description = $validatedData['description'];
        $this->article->author_id = $this->profileId;
        $this->article->entity_type_id = self::ENTITY_TYPE;
        $this->article->category_id = self::CATEGORY;
        $this->article->status_id = Article::ENTITY_STATUS_UNDER_MODERATION;

        DB::beginTransaction();

        try {

            $this->article->save();

            $currentId = Article::latest()->first()->id ?? 0;

            if ($request->files->get('files')) {
                UploadImagesService::save(
                    self::ENTITY_TYPE,
                    $currentId,
                    $request->files->get('files'),
                    true,
                    true
                );
            }

//            $cat = [1,4,5];
//            $c = Category::find($cat);
//            $this->article->categories()->attach($c);

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
                        $exception->getMessage()
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

        if (count($article) > 0) {
            return \response()->json([
                'success' => true,
                'data' => $article
            ], 200);
        } else {
            return \response()->json([
                'success' => false,
                'data' => $article
            ], 404);
        }
    }

    public function update(ArticleRequest $request, $id): JsonResponse
    {
        try {
            $article = Article::find($id);
            $article->title = $request['title'];
            $article->description = $request['description'];

            DB::beginTransaction();

            if ($request->files->get('files')) {
                UploadImagesService::save(
                    self::ENTITY_TYPE,
                    $id,
                    $request->files->get('files'),
                    true,
                    false,
                );
            }

            $article->update();
            DB::commit();

            return \response()->json([
                'success' => true,
                'data' => $request->files->get('files')
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();

            return \response()->json([
                'success' => true,
                'data' => $exception->getMessage()
            ], 200);
        }
    }
}
