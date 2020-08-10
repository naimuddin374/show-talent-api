<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserModel;
use App\UserInfoModel;
use File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function adminView()
    {
        $data = UserModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = UserModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = UserModel::with(['userInfo', 'education', 'experience', 'follower', 'following.user'])->where(['id' => $id])->first();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $user = UserModel::where('email', $post['email'])->get();
        if(isset($user[0])){
            return response()->json(['message' => 'This email already exist!'], 406);
        }
        $validator = Validator::make($post, [
            'full_name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['validation' => $validator->errors(), 'message' => 'Validation Error!'], 406);
        }
        $data = [
            "full_name" => $post['full_name'],
            "name" => $post['name'],
            "contact" => $post['contact'],
            "email" => $post['email'],
            "password" => Hash::make($post['password']),
            "type" => $post['type'],
            "status" => $post['status'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        $id = UserModel::create($data)->id;
        if(@$post['dob']){
            $result = UserInfoModel::create(['user_id' => $id, 'dob' => $post['dob']])->id;
        }else{
            $result = $id;
        }
        if($result){
            return response()->json(["message" => "Created successful."], 201);
        } else{
            return response()->json(["message" => "Server too busy."], 400);
        }
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "full_name" => $post['full_name'],
            "name" => $post['name'],
            "contact" => $post['contact'],
            "email" => $post['email'],
            "type" => $post['type'],
            "status" => $post['status'],
        ];
        if($post['password']){
            $data['password'] = Hash::make($post['password']);
        }
        $row = UserModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = UserModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}