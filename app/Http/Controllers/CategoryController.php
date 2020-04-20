<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CategoryModel;


class CategoryController extends Controller
{
    public function adminView()
    {
        $data = CategoryModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = CategoryModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = CategoryModel::where('id', $id)->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "name" => $post['name'],
            "status" => $post['status'],
        ];
        CategoryModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "name" => $post['name'],
            "status" => $post['status'],
        ];
        $row = CategoryModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = CategoryModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
