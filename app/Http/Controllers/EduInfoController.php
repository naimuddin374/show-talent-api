<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EduInfoModel;


class EduInfoController extends Controller
{
    public function adminView()
    {
        $data = EduInfoModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = EduInfoModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = EduInfoModel::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = EduInfoModel::where('user_id', $id)->orderBy('passing_year', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required|numeric',
            'status' => 'required|numeric',
            'degree' => 'required',
            'institute' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "status" => $post['status'],
            "degree" => $post['degree'],
            "institute" => $post['institute'],
            "passing_year" => $post['passing_year'],
        ];
        EduInfoModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "status" => $post['status'],
            "degree" => $post['degree'],
            "institute" => $post['institute'],
            "passing_year" => $post['passing_year'],
        ];
        $row = EduInfoModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = EduInfoModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}