<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostModel;
use App\AdModel;
use File;


class AdController extends Controller
{
    public function adminView()
    {
        $data = AdModel::leftJoin('users', 'users.id', '=', 'ads.user_id')
                ->select('users.name as user_name','ads.*')
                ->orderBy('ads.id', 'DESC')
                ->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = AdModel::leftJoin('users', 'users.id', '=', 'ads.user_id')
                ->select('users.name as user_name','ads.*')
                ->orderBy('ads.id', 'DESC')
                ->where('ads.id',$id)
                ->get();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = AdModel::leftJoin('users', 'users.id', '=', 'ads.user_id')
                ->select('users.name as user_name','ads.*')
                ->orderBy('ads.id', 'DESC')
                ->where('users.id',$id)
                ->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = AdModel::leftJoin('users', 'users.id', '=', 'ads.user_id')
                ->select('users.name as user_name','posts.*')
                ->where('ads.status', 1)
                ->orderBy('ads.id', 'DESC')
                ->get();
        return response()->json($data, 200);
    }

    public function approve($id)
    {
        $data = AdModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = AdModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
    }



    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "page_id" => $post['page_id'],
            "category_id" => $post['category_id'],
            "title" => $post['title'],
            "video" => $post['video'],
            "hyperlink" => $post['hyperlink'],
            "start_date" => $post['start_date'],
            "end_date" => $post['end_date'],
            "status" => $post['status'],
        ];
        AdModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "category_id" => $post['category_id'],
            "title" => $post['title'],
            "vide" => $post['vide'],
            "hyperlink" => $post['hyperlink'],
            "start_date" => $post['start_date'],
            "end_date" => $post['end_date'],
            "status" => $post['status'],
            "reject_note" => $post['reject_note'],
            "reopen_note" => $post['reopen_note'],
        ];
        AdModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = AdModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
