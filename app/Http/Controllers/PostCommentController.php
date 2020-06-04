<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostCommentModel;


class PostCommentController extends Controller
{
    public function adminView()
    {
        $data = PostCommentModel::leftJoin('users', 'users.id', '=', 'comments.user_id')
                ->leftJoin('posts', 'posts.id', '=', 'comments.post_id')
                ->select('users.name as user_name', 'posts.title as post_title', 'comments.*')
                ->orderBy('comments.id', 'DESC')
                ->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PostCommentModel::leftJoin('users', 'users.id', '=', 'comments.user_id')
                ->leftJoin('posts', 'posts.id', '=', 'comments.post_id')
                ->select('users.name as user_name', 'posts.title as post_title', 'comments.*')
                ->orderBy('comments.id', 'DESC')
                ->where('comments.status', 1)
                ->get();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = PostCommentModel::with(['user'])->where(['post_id'=> $id])->get();
        // $data = PostCommentModel::where('post_id', $id)->get();
        return response()->json($data, 200);
    }


    public function approve($id)
    {
        $data = PostCommentModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = PostCommentModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
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
            "status" => $post['status'],
            "comment" => strip_tags($post['comment']),
            "comment_id" => @$post['comment_id'],
        ];
        PostCommentModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "status" => $post['status'],
            "comment" => strip_tags($post['comment']),
        ];
        $row = PostCommentModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = PostCommentModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}