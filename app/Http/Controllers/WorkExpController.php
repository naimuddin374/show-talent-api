<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\WorkExpModel;


class WorkExpController extends Controller
{
    public function adminView()
    {
        $data = WorkExpModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = WorkExpModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = WorkExpModel::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required',
            'job_title' => 'required|string',
            'company' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "job_title" => $post['job_title'],
            "company" => $post['company'],
            "start_date" => $post['start_date'],
            "end_date" => $post['end_date'],
            "status" => $post['status'],
        ];
        WorkExpModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "job_title" => $post['job_title'],
            "company" => $post['company'],
            "start_date" => $post['start_date'],
            "end_date" => $post['end_date'],
            "status" => $post['status'],
        ];
        $row = WorkExpModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = WorkExpModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}