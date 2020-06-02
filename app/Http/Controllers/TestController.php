<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostModel;

class TestController extends Controller
{
    public function index(){
        $data = PostModel::with(['user', 'category', 'page', 'likes'])
                ->where(['user_id'=> 5])
                ->get();
        return $data;
    }
}
