<?php

use App\Http\Controllers\admin\article\AdminArticleController;
use App\Http\Controllers\admin\article\ArticleSearchController;
use App\Http\Controllers\api\Auth\LoginAdminController;
use App\Http\Controllers\api\Auth\LoginController;
use App\Http\Controllers\api\Auth\RegisterController;
use App\Http\Controllers\api\Auth\SocialLoginController;
use App\Http\Controllers\api\MeController;
use App\Http\Controllers\PersonalCabinetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

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

Route::group(['prefix' => '/auth'], function () {
   Route::post('register', [RegisterController::class, 'register']);
   Route::post('login', [LoginController::class, 'login']);

   //social
    Route::get('/login/{service}', [SocialLoginController::class, 'redirect']);
    Route::get('/login/{service}/callback', [SocialLoginController::class, 'callBack']);
});

Route::get('/articles', [ArticleController::class, 'showAll'])->name('articles.index');
Route::get('/article/{article}' ,[ArticleController::class, 'showOne'])->name('article.showOne');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/me', [MeController::class, 'index']);
    Route::get('/auth/logout', [MeController::class, 'logout']);

    //Personal Cabinet
    Route::get('/personal-cabinet', [MeController::class, 'logout']);
    Route::get('/my-articles', [PersonalCabinetController::class, 'getMyArticles'])->name('pc.articles.index');
    Route::get('/article/create/', [PersonalCabinetController::class, 'create'])->name('articles.create');
    Route::get('/article/edit/{id}' ,[PersonalCabinetController::class, 'edit'])->name('article.edit');
    Route::post('/article/store', [PersonalCabinetController::class, 'store'])->name('article.store');
    Route::post('/article/{article}', [PersonalCabinetController::class, 'update'])->name('article.update');
});

Route::group(['prefix' => '/admin'], function () {
    Route::post('login', [LoginAdminController::class, 'login']);

    // Search
    Route::get('/article/search', [ArticleSearchController::class, 'search']);

    Route::get('/articles', [AdminArticleController::class, 'showAll'])->name('admin.articles.index');
    Route::get('/article/edit/{id}' ,[AdminArticleController::class, 'edit'])->name('admin.article.edit');
    Route::patch('/article/approve/{id}' ,[AdminArticleController::class, 'approve'])->name('admin.article.approve');
    Route::delete('/article/delete/{id}' ,[AdminArticleController::class, 'delete'])->name('admin.article.delete');
    Route::get('/article-categories', [AdminArticleController::class, 'getCategories'])->name('article-categories.index');
});
