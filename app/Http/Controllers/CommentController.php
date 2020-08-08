<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CommentModel;


class CommentController extends Controller
{
    public function adminView()
    {
        $data = CommentModel::with(['user', 'likes'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = CommentModel::with(['user', 'likes'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = CommentModel::with(['user', 'likes'])->where(['ebook_id'=> $id])->orderBy('id', 'DESC')->get();
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
        $row = CommentModel::findOrFail($id);
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
        $row = CommentModel::findOrFail($id);
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
        $row = CommentModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        CommentModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
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
            'created_at' => date('Y-m-d H:i:s')
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
