<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostCommentModel;


class PostCommentController extends Controller
{
    public function adminView()
    {
        $data = PostCommentModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = PostCommentModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = PostCommentModel::with(['user'])->where(['post_id'=> $id])->get();
        return response()->json($data, 200);
    }

    public function approve(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 1,
            "admin_id" => $auth['id'],
            "reject_note" => null,
            'is_unread' => 1
        ];
        $row = PostCommentModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 2,
            "reject_note" => $post['reject_note'],
            "admin_id" => $auth['id'],
            'is_unread' => 1
        ];
        $row = PostCommentModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Rejected successfully."], 201);
    }
    public function unpublish(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 3,
            "admin_id" => $auth['id'],
            "reject_note" => null,
            'is_unread' => 1
        ];
        $row = PostCommentModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        PostCommentModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
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
