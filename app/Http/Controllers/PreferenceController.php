<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PreferenceModel;


class PreferenceController extends Controller
{
    public function adminView()
    {
        $data = PreferenceModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PreferenceModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function getByUserId($id)
    {
        $data = PreferenceModel::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required|numeric',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "category_id" => $post['category_id'],
        ];
        PreferenceModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "category_id" => $post['category_id'],
        ];
        $row = PreferenceModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = PreferenceModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
