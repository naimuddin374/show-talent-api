<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostModel;
use App\EbookModel;
use File;

class TestController extends Controller
{
    public function index(){

        $data = EbookModel::with(['user', 'page', 'category', 'comments.likes'])->with(['chapter' => function($q) {
            $q->where('status', '=', 1);
        }])->where(['status' => 1])->orderBy('id', 'desc')->get();
        return $data;
        return response()->json($data, 200);
    }
}