<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostModel;
use File;

class TestController extends Controller
{
    public function index(){

        $data = ['abc' => 'OK'];
        return response()->json($data, 200);
    }
}
