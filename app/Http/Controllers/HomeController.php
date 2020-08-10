<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserModel;
use App\PageModel;
use App\PostModel;
use App\ClassifiedModel;
use App\EbookModel;
use App\AdModel;

class HomeController extends Controller
{
    public function getPosts(){
        $preferences = getUserPreference();
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user'])->where(['status' => 1])->whereIn('category_id', $preferences)->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getClassifieds(){
        $preferences = getUserPreference();
        $data = ClassifiedModel::where(['status' => 1])->whereIn('category_id', $preferences)->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getEbooks(){
        $preferences = getUserPreference();
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments.likes'])->where(['status' => 1])->whereIn('category_id', $preferences)->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getTopTalentList(){
        $data = UserModel::with(['experience'])->where('status', 1)->orderBy('points', 'DESC')->limit(5)->get();
        return response()->json($data, 200);
    }

    public function getAds(){
        $preferences = getUserPreference();
        $data = AdModel::where('status', 1)->whereIn('category_id', $preferences)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }
}