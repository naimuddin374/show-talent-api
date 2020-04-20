<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$arr = [
    'category' => 'CategoryController',
    'chapter' => 'ChapterController',
    'classified' => 'ClassifiedController',
    'country' => 'CountryController',
    'ebook' => 'EbookController',
    'education-info' => 'EduInfoController',
    'page' => 'PageController',
    'post' => 'PostController',
    'preference' => 'PreferenceController',
    'product-category' => 'ProductCatController',
    'review' => 'ReviewController',
    'user-info' => 'UserInfoController',
    'work-experience' => 'WorkExpController',
    'user' => 'UserController',
    'comment' => 'CommentController',
];

foreach ($arr as $key => $value) {
    Route::get("$key", "$value@view");
    Route::get("admin/$key", "$value@adminView");
    Route::get("$key/{id}", "$value@detail");
    Route::post("{$key}", "$value@store");
    Route::put("{$key}/{id}", "$value@update");
    Route::delete("{$key}/{id}", "$value@delete");
    Route::get("{$key}/approve/{id}", "$value@approve");
    Route::get("{$key}/reject/{id}", "$value@reject");
}

Route::get("admin/get-post/{type}", "PostController@postShowByType");