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


Route::group(['middleware' => ['jwt.verify']], function() {

    $arr = [
        'profile' => 'ProfileController',
        'category' => 'CategoryController',
        'country' => 'CountryController',
        'city' => 'CityController',
        'user-info' => 'UserInfoController',
        'education' => 'EduInfoController',
        'experience' => 'WorkExpController',
        'preference' => 'PreferenceController',
        'followers' => 'FollowerController',
        'post' => 'PostController',
        'page' => 'PageController',
        'user' => 'UserController',
        'classified' => 'ClassifiedController',
        'ebook' => 'EbookController',
        'chapter' => 'ChapterController',
        'product-category' => 'ProductCatController',
        'review' => 'ReviewController',
        'comment' => 'CommentController',
        'posted-ad' => 'AdController',
        'account' => 'AccountController',
        'post-like' => 'PostLikeController',
        'post-comment' => 'PostCommentController',
    ];
   foreach ($arr as $key => $value) {
        Route::get("$key", "$value@view");
        Route::get("$key/{id}", "$value@detail");
        Route::get("$key/join/{id}", "$value@viewByJoinId");
        Route::get("$key/page/{id}", "$value@getByPage");
        Route::get("admin/$key", "$value@adminView");
        Route::post("{$key}", "$value@store");
        Route::put("{$key}/{id}", "$value@update");
        Route::put("{$key}/update/photo/{id}", "$value@updatePhoto");
        Route::delete("{$key}/{id}", "$value@delete");
        Route::put("{$key}/approve/{id}", "$value@approve");
        Route::put("{$key}/reject/{id}", "$value@reject");
    }
    Route::get("admin/get-post/{type}", "PostController@postShowByType");

    Route::get("auth/user/info", "ProfileController@getAuthUserInfo");
    Route::post("profile/photo/update", "ProfileController@updateProfilePhoto");
    Route::get("auth/token/refresh", "AuthController@refreshToken");
    Route::get("following/follower/{id}/{isPage}", "FollowerController@gerFollowerFollowing");

    Route::put("ebook/cover/photo/{id}", "EbookController@uploadCoverPhoto");
});




Route::post("auth/registration", "UserController@store");
Route::post("auth/login", "AuthController@authenticate");
// Route::get("test", "CategoryController@test");
Route::get("test", "TestController@index");
Route::get("print-pdf/{id}", "EbookController@printPDF");
