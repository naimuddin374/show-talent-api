<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AccountModel;
use App\UserModel;


class AccountController extends Controller
{
    public function adminView()
    {
        $data = AccountModel::leftJoin('users', 'users.id', '=', 'accounts.user_id')->select('users.name as user_name','accounts.*')->orderBy('accounts.id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = AccountModel::leftJoin('users', 'users.id', '=', 'accounts.user_id')->select('users.name as user_name','accounts.*')->orderBy('accounts.id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = AccountModel::leftJoin('users', 'users.id', '=', 'accounts.user_id')->select('users.name as user_name','accounts.*')->where('accounts.id', $id)->orderBy('accounts.id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function getByUserId($id)
    {
        $data = AccountModel::leftJoin('users', 'users.id', '=', 'accounts.user_id')->select('users.name as user_name','accounts.*')->where('users.id', $id)->orderBy('accounts.id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function approve($id)
    {
        $data = AccountModel::where('id', $id)->update(['status' => 1]);
        $data = AccountModel::where('id', $id)->get();
        UserModel::where('id', $data[0]->user_id)->update('balance', $data[0]->available_balance);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = AccountModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
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
            "status" => $post['status'],
            "type" => $post['type'],
            "last_balance" => $post['last_balance'],
            "amount" => $post['amount'],
            "available_balance" => $post['available_balance'],
            "comment" => $post['comment'],
        ];
        AccountModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "status" => $post['status'],
            "comment" => $post['comment'],
        ];
        $row = AccountModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = AccountModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}