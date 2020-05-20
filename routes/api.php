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
        'category' => 'CategoryController',
        'chapter' => 'ChapterController',
        'classified' => 'ClassifiedController',
        'country' => 'CountryController',
        'city' => 'CityController',
        'ebook' => 'EbookController',
        'education' => 'EduInfoController',
        'page' => 'PageController',
        'post' => 'PostController',
        'preference' => 'PreferenceController',
        'product-category' => 'ProductCatController',
        'review' => 'ReviewController',
        'user-info' => 'UserInfoController',
        'experience' => 'WorkExpController',
        'user' => 'UserController',
        'comment' => 'CommentController',
        'posted-ad' => 'AdController',
        'account' => 'AccountController',
        'profile' => 'ProfileController',
        'followers' => 'FollowerController',
    ];
   foreach ($arr as $key => $value) {
        Route::get("$key", "$value@view");
        Route::get("$key/{id}", "$value@detail");
        Route::get("$key/join/{id}", "$value@viewByJoinId");
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
});




Route::post("auth/registration", "UserController@store");
Route::post("auth/login", "AuthController@authenticate");