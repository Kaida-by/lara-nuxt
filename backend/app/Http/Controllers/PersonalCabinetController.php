<?php

namespace App\Http\Controllers;

use App\Events\Notifications;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Article;
use App\Models\Profile;
use App\Notifications\CreateEntityNotification;
use App\Notifications\DeleteEntityNotification;
use App\Notifications\UpdateEntityNotification;
use App\Notifications\UserNotification;
use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use function response;

class PersonalCabinetController
{
    const ENTITY_TYPE = 2;
    const CATEGORY = 2;
    const ENTITY_NAME = 'acticle';

    protected Article $article;
    protected $profileId;
    protected $auth;

    public function __construct(Article $article)
    {
        $this->article = $article;
        $this->profileId = Profile::select('id')->where(['user_id' => 1])->limit(1)->get()[0]->id;
    }

    public function getMyArticles(): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', self::ENTITY_TYPE);
            }
        ])
            ->where(['status_id' => EntityHelper::ENTITY_STATUS_ACTIVE])
            ->where(['author_id' => $this->profileId])
            ->simplePaginate(10);

        return ArticleResource::collection($articles);
    }

    public function store(ArticleRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $this->article->title = $validatedData['title'];
        $this->article->description = $validatedData['description'];
        $this->article->author_id = $this->profileId;
        $this->article->entity_type_id = self::ENTITY_TYPE;
        $this->article->category_id = self::CATEGORY;
        $this->article->status_id = EntityHelper::ENTITY_STATUS_UNDER_MODERATION;

        try {
            DB::beginTransaction();
        } catch (Throwable $e) {
            dd($e);
        }

        try {

            $this->article->save();

            $currentId = Article::latest()->first()->id ?? 0;

            if ($request->files->get('files')) {
                UploadImagesService::save(
                    self::ENTITY_TYPE,
                    $currentId,
                    $request->files->get('files'),
                    true,
                );
            }

            DB::commit();

            $user = \Auth::user();
            $user->notify(new CreateEntityNotification(self::ENTITY_NAME, $this->article->title));
            event(new Notifications($user->id));

            return response()->json([
                'success' => true,
                'article' => $this->article
            ]);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
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
            'images' => function($q) {
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $id])
            ->get();

        if (count($article) > 0) {
            return response()->json([
                'success' => true,
                'data' => $article
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => $article
            ], 404);
        }
    }

    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        try {
            DB::beginTransaction();

            $article->update($request->except('images'));

            $oldImages = $request->images;
            $images = [];

            if ($oldImages) {
                foreach ($oldImages as $key => $image) {
                    if (is_string($image)) {
                        $oldImage = json_decode($image);
                        $images[$key] = $oldImage;
                    } else {
                        $images[$key] = $image;
                    }
                }
            }

            UploadImagesService::deleteMissingImages($article, $images);

            if ($images) {
                UploadImagesService::save(
                    self::ENTITY_TYPE,
                    $article->id,
                    $images,
                    true,
                );
            }

            DB::commit();

            $user = \Auth::user();
            $user->notify(new UpdateEntityNotification(self::ENTITY_NAME, $article->title));
            event(new Notifications($user->id));

            return response()->json([
                'success' => true,
                'data' => $request['images']
            ]);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'success' => true,
                'data' => $exception->getMessage()
            ]);
        } catch (Throwable $e) {
            dd($e);
        }
    }
}
