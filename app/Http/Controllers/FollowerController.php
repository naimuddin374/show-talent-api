<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\FollowerModel;

class FollowerController extends Controller
{
    public function adminView()
    {
        $data = FollowerModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = FollowerModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = FollowerModel::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = FollowerModel::where('profile_id', $id)->get();
        return response()->json($data, 200);
    }

    public function gerFollowerFollowing($id, $isPage)
    {
        $following = FollowerModel::where(['user_id' => $id, 'is_page' => $isPage])->count();
        $followers = FollowerModel::where(['profile_id' => $id, 'is_page' => $isPage])->count();
        return response()->json(['following' => $following, 'followers' => $followers], 200);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'profile_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "profile_id" => $post['profile_id'],
            "user_id" => $post['user_id'],
        ];
        if(@$post['is_page']){
            $data['is_page'] = 1;
        }
        $oldData = FollowerModel::where($data)->first();
        if($oldData){
            return response()->json(["message" => "You Already Following This Profile."], 201);
        }else{
            FollowerModel::create($data);
            return response()->json(["message" => "Created successful."], 201);
        }
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "profile_id" => $post['profile_id'],
            "user_id" => $post['user_id'],
        ];
        $row = FollowerModel::where($data)->delete();
        return response()->json(["message" => "Unfollowing Successful."], 201);
    }


    public function delete($id)
    {
        $row = FollowerModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}