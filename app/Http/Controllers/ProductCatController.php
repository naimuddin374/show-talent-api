<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ProductCatModel;


class ProductCatController extends Controller
{
    public function adminView()
    {
        $data = ProductCatModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = ProductCatModel::orderBy('id', 'DESC')->get();
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
        ];
        ProductCatModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "name" => $post['name'],
        ];
        $row = ProductCatModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = ProductCatModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}