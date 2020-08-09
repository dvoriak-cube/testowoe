<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Article;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    
    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');
    
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');
    Route::get('articles/byuser/{id}', 'ArticleController@users_posts');
    Route::get('articles/bycategory/{category}', 'ArticleController@ctgry_posts');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'UserController@logout');
        Route::get('details', 'UserController@details');
        Route::get('articles/favorites/{id}', 'ArticleController@fvrite_posts');
        Route::post('articles', 'ArticleController@store');
        Route::put('articles/{article}', 'ArticleController@update');
        Route::delete('articles/{article}', 'ArticleController@delete');

        Route::post('articlelike', 'ArticleLikeController@create');
        Route::delete('articlelike/{id}', 'ArticleLikeController@destroy');
    });

});