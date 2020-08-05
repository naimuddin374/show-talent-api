<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostModel;
use App\EbookModel;
use File;

class TestController extends Controller
{
    public function index(){
        addRewardPoint(3, 10);
        echo 'OK';
        exit;
        return response()->json($data, 200);
    }
}