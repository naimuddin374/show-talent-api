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
        'account' => 'AccountController',
        'post-like' => 'PostLikeController',
        'post-comment' => 'PostCommentController',
        'comment-like' => 'CommentLikeController',
        'advartisment' => 'AdController',
        'ad-audience' => 'AdAudienceController',
        'ad-budget' => 'AdBudgetController',
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
        Route::put("{$key}/unpublished/{id}", "$value@unpublish");
        Route::get("{$key}/read/all", "$value@readAll");
    }
    Route::get("admin/get-post/{type}", "PostController@postShowByType");
    Route::put("editor/pick/{id}", "PostController@editorPickHandle");

    Route::get("auth/user/info", "ProfileController@getAuthUserInfo");
    Route::post("profile/photo/update", "ProfileController@updateProfilePhoto");
    Route::get("auth/token/refresh", "AuthController@refreshToken");
    Route::get("following/follower/{id}/{isPage}", "FollowerController@gerFollowerFollowing");
    Route::get("followers/{id}/{isPage}", "FollowerController@getFollowerList");

    Route::get("profile/follower/{profileId}/{isPage}", "FollowerController@getProfileFollower");

    Route::put("ebook/cover/photo/{id}", "EbookController@uploadCoverPhoto");
    Route::get("admin/pending/count", "AdminController@countPending");

    Route::get("unread/all/posts", "ProfileController@getAllUnread");
    Route::get("read/all/posts", "ProfileController@changeAllRead");

    Route::delete("profile/image/remove/{id}", "ProfileController@deleteProfilePic");
    Route::delete("page/image/remove/{id}", "PageController@deleteProfilePic");

    // Get ebook draft list
    Route::get("ebook/draft/list/{userId}/{pageId}", "EbookController@getDraftList");

    
    Route::get("advartisment/list/user/{id}", "AdController@getUserAdList");
    Route::get("advartisment/list/page/{id}", "AdController@viewPageAdList");

    Route::get("home/posts", "HomeController@getPosts");
    Route::get("home/classified", "HomeController@getClassifieds");
    Route::get("home/ebooks", "HomeController@getEbooks");
    Route::get("top/talents", "HomeController@getTopTalentList");
    Route::get("home/advartisment", "HomeController@getAds");
});




Route::post("auth/registration", "UserController@store");
Route::post("auth/login", "AuthController@authenticate");
Route::post("auth/admin/login", "AuthController@adminAuthenticate");
Route::post("auth/forgot/password", "AuthController@forgotPassword");
Route::post("auth/password/reset", "AuthController@resetPassword");
// Route::get("test", "CategoryController@test");
Route::get("test", "TestController@index");