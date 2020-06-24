<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CommentModel;


class CommentController extends Controller
{
    public function adminView()
    {
        $data = CommentModel::with(['user'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = CommentModel::with(['user'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = CommentModel::with(['user'])->where(['ebook_id'=> $id])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function approve($id)
    {
        $data = CommentModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = CommentModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'ebook_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "ebook_id" => $post['ebook_id'],
            "rating" => $post['rating'],
            "comment" => strip_tags($post['comment']),
        ];
        CommentModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }

    public function delete($id)
    {
        $row = CommentModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}