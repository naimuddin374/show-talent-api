<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserInfoModel;


class UserInfoController extends Controller
{
    public function adminView()
    {
        $data = UserInfoModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = UserInfoModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = UserInfoModel::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "country_id" => $post['country_id'],
            "city_id" => $post['city_id'],
            "address" => $post['address'],
            "dob" => $post['dob'],
            "gander" => $post['gander'],
            "bio" => $post['bio'],
        ];
        UserInfoModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "country_id" => $post['country_id'],
            "city_id" => $post['city_id'],
            "address" => $post['address'],
            "dob" => $post['dob'],
            "gander" => $post['gander'],
            "bio" => $post['bio'],
        ];
        $row = UserInfoModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = UserInfoModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
