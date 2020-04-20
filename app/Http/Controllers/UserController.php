<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserModel;
use File;

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
        $data = UserModel::where('id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }



    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'full_name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "full_name" => $post['full_name'],
            "name" => $post['name'],
            "contact" => $post['contact'],
            "email" => $post['email'],
            "password" => md5($post['password']),
            "type" => $post['type'],
            "status" => $post['status'],
        ];
        UserModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
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
            $data['password'] = md5($post['password']);
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
