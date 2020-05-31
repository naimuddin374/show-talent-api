<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostLikeModel;
use App\PostModel;

class PostLikeController extends Controller
{
    public function adminView()
    {
        $data = PostLikeModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PostLikeModel::orderBy('name', 'ASC')->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = PostLikeModel::where('id', $id)->first();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required|numeric',
            'post_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "post_id" => $post['post_id'],
        ];
        PostLikeModel::create($data)->id;
        return response()->json(["message" => "Post Liked Successfully."], 201);
    }


    public function update(Request $request, $id)
    {
    }


    public function delete($id)
    {
        $row = PostLikeModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
