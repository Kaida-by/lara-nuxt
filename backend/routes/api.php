<?php

use App\Http\Controllers\Admin\Articles\AdminArticleController;
use App\Http\Controllers\Admin\Articles\ArticleSearchController;
use App\Http\Controllers\Admin\CV\AdminCVController;
use App\Http\Controllers\Admin\CV\CVSearchController;
use App\Http\Controllers\Admin\Vacancy\VacancySearchController;
use App\Http\Controllers\Admin\Posters\AdminPosterController;
use App\Http\Controllers\Admin\Posters\PosterSearchController;
use App\Http\Controllers\Admin\Vacancy\AdminVacancyController;
use App\Http\Controllers\API\Auth\LoginAdminController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\SocialLoginController;
use App\Http\Controllers\API\MeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PersonalCabinet\ArticlePersonalCabinetController;
use App\Http\Controllers\PersonalCabinet\CVPersonalCabinetController;
use App\Http\Controllers\PersonalCabinet\PosterPersonalCabinetController;
use App\Http\Controllers\PersonalCabinet\VacancyPersonalCabinetController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\VacancyController;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::group(['prefix' => '/auth'], static function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);

   //social
    Route::get('/login/{service}', [SocialLoginController::class, 'redirect']);
    Route::get('/login/{service}/callback', [SocialLoginController::class, 'callBack']);
});

//Articles
Route::get('/articles', [ArticleController::class, 'showAll'])->name('articles.index');
Route::get('/article/{article}' ,[ArticleController::class, 'showOne'])->name('article.showOne');

//Posters
Route::get('/posters', [PosterController::class, 'showAll'])->name('posters.index');
Route::get('/poster/{poster}' ,[PosterController::class, 'showOne'])->name('poster.showOne');

//CVs
Route::get('/cvs', [CVController::class, 'showAll'])->name('cvs.index');
Route::get('/cv/{cv}' ,[CVController::class, 'showOne'])->name('cvs.showOne');

//Vacancies
Route::get('/vacancies', [VacancyController::class, 'showAll'])->name('vacancies.index');
Route::get('/vacancy/{vacancy}' ,[VacancyController::class, 'showOne'])->name('vacancies.showOne');

Route::group(['middleware' => 'jwt.auth'], static function () {
    Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);
    Route::get('/me', [MeController::class, 'index']);
    Route::get('/auth/logout', [MeController::class, 'logout']);
    Route::get('/get-notifications', [NotificationsController::class, 'getNotifications']);
    Route::delete('/remove-notification/{id}', [NotificationsController::class, 'removeNotifications']);
    Route::post('/set-mark-as-read/{uuid}', [NotificationsController::class, 'setMarkAsReadNotification']);

    //Personal Cabinet
    Route::get('/personal-cabinet', [MeController::class, 'logout']);
    Route::post('/upload-image/', [UploadImageController::class, 'upload'])->name('image.upload');

        // Articles
        Route::get('/my-articles', [ArticlePersonalCabinetController::class, 'getMyArticles'])->name('pc.articles.index');
        Route::get('/article/create/', [ArticlePersonalCabinetController::class, 'create'])->name('articles.create');
        Route::get('/article/edit/{id}' ,[ArticlePersonalCabinetController::class, 'edit'])->name('article.edit');
        Route::post('/article/{article}', [ArticlePersonalCabinetController::class, 'update'])->name('article.update');
        Route::get('/get-article-categories', [ArticlePersonalCabinetController::class, 'getCategories'])->name('article.get-categories');
            //Create temporary Entity
            Route::post('/article-cte', [ArticlePersonalCabinetController::class, 'createTemporaryArticle'])->name('article.cte');
            Route::get('/get-last-article-cte', [ArticlePersonalCabinetController::class, 'getLastArticle'])->name('last.article.cte');

        //Posters
        Route::get('/my-posters', [PosterPersonalCabinetController::class, 'getMyPosters'])->name('pc.posters.index');
        Route::get('/poster/edit/{id}' ,[PosterPersonalCabinetController::class, 'edit'])->name('poster.edit');
        Route::post('/poster/{poster}', [PosterPersonalCabinetController::class, 'update'])->name('poster.update');
        Route::get('/get-poster-categories', [PosterPersonalCabinetController::class, 'getCategories'])->name('poster.get-categories');
            //Create temporary Entity
            Route::post('/poster-cte', [PosterPersonalCabinetController::class, 'createTemporaryPoster'])->name('poster.cte');

        //CVs
        Route::get('/my-cvs', [CVPersonalCabinetController::class, 'getMyCVs'])->name('pc.cvs.index');
        Route::get('/cv/edit/{id}' ,[CVPersonalCabinetController::class, 'edit'])->name('cv.edit');
        Route::post('/cv/{cv}', [CVPersonalCabinetController::class, 'update'])->name('cv.update');
        Route::get('/get-cv-categories', [CVPersonalCabinetController::class, 'getCategories'])->name('cv.get-categories');
            //Create temporary Entity
            Route::post('/cv-cte', [CVPersonalCabinetController::class, 'createTemporaryCV'])->name('cv.cte');

        //Vacancies
        Route::get('/my-vacancies', [VacancyPersonalCabinetController::class, 'getMyVacancies'])->name('pc.vacancies.index');
        Route::get('/vacancy/edit/{id}' ,[VacancyPersonalCabinetController::class, 'edit'])->name('vacancy.edit');
        Route::post('/vacancy/{vacancy}', [VacancyPersonalCabinetController::class, 'update'])->name('vacancy.update');
        Route::get('/get-vacancy-categories', [VacancyPersonalCabinetController::class, 'getCategories'])->name('vacancy.get-categories');
            //Create temporary Entity
            Route::post('/vacancy-cte', [VacancyPersonalCabinetController::class, 'createTemporaryVacancy'])->name('vacancy.cte');
});

// Admin
Route::group(['prefix' => '/admin'], static function () {
    Route::post('login', [LoginAdminController::class, 'login']);

        // Article
        Route::get('/articles', [AdminArticleController::class, 'showAll'])->name('admin.articles.index');
        Route::get('/article/edit/{id}' ,[AdminArticleController::class, 'edit'])->name('admin.article.edit');
        Route::patch('/article/approve/{id}' ,[AdminArticleController::class, 'approve'])->name('admin.article.approve');
        Route::delete('/article/delete/{id}' ,[AdminArticleController::class, 'delete'])->name('admin.article.delete');
        Route::get('/article-categories', [AdminArticleController::class, 'getCategories'])->name('admin.article-categories.index');
            // Search
            Route::get('/article/search', [ArticleSearchController::class, 'search']);
            //GetCountAllArticles
            Route::get('/count-articles', [AdminArticleController::class, 'getCountAllArticles']);

        // Poster
        Route::get('/posters', [AdminPosterController::class, 'showAll'])->name('admin.posters.index');
        Route::get('/poster/edit/{id}' ,[AdminPosterController::class, 'edit'])->name('admin.poster.edit');
        Route::patch('/poster/approve/{id}' ,[AdminPosterController::class, 'approve'])->name('admin.poster.approve');
        Route::delete('/poster/delete/{id}' ,[AdminPosterController::class, 'delete'])->name('admin.poster.delete');
        Route::get('/poster-categories', [AdminPosterController::class, 'getCategories'])->name('admin.poster-categories.index');
            // Search
            Route::get('/poster/search', [PosterSearchController::class, 'search']);
            //CountAllPosters
            Route::get('/count-posters', [AdminPosterController::class, 'getCountAllPosters']);

        // CV
        Route::get('/cvs', [AdminCVController::class, 'showAll'])->name('admin.cvs.index');
        Route::get('/cv/edit/{id}' ,[AdminCVController::class, 'edit'])->name('admin.cv.edit');
        Route::patch('/cv/approve/{id}' ,[AdminCVController::class, 'approve'])->name('admin.cv.approve');
        Route::delete('/cv/delete/{id}' ,[AdminCVController::class, 'delete'])->name('admin.cv.delete');
        Route::get('/cv-categories', [AdminCVController::class, 'getCategories'])->name('admin.cv-categories.index');
            // Search
            Route::get('/cv/search', [CVSearchController::class, 'search']);
            //CountAllCVs
            Route::get('/count-cvs', [AdminCVController::class, 'getCountAllCVs']);

        // Vacancy
        Route::get('/vacancies', [AdminVacancyController::class, 'showAll'])->name('admin.vacancies.index');
        Route::get('/vacancy/edit/{id}' ,[AdminVacancyController::class, 'edit'])->name('admin.vacancy.edit');
        Route::patch('/vacancy/approve/{id}' ,[AdminVacancyController::class, 'approve'])->name('admin.vacancy.approve');
        Route::delete('/vacancy/delete/{id}' ,[AdminVacancyController::class, 'delete'])->name('admin.vacancy.delete');
        Route::get('/vacancy-categories', [AdminVacancyController::class, 'getCategories'])->name('admin.vacancy-categories.index');
            // Search
            Route::get('/vacancy/search', [VacancySearchController::class, 'search']);
            //CountAllVacancies
            Route::get('/count-vacancies', [AdminVacancyController::class, 'getCountAllVacancies']);
});
