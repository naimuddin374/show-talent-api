<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CommentLikeModel;

class CommentLikeController extends Controller
{
    public function adminView()
    {
        $data = CommentLikeModel::with(['user', 'comment'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = CommentLikeModel::with(['user', 'comment'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = CommentLikeModel::with(['user', 'comment'])->where(['ebook_id'=> $id])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'comment_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "comment_id" => $post['comment_id'],
            "type" => $post['type'],
        ];
        $id = CommentLikeModel::create($data)->id;
        return response()->json(["message" => "Created successful.", 'insertId' => $id], 201);
    }

    public function delete($id)
    {
        $row = CommentLikeModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}