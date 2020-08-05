<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CityModel;

class CityController extends Controller
{
    public function adminView()
    {
        $data = CityModel::with(['country'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = CityModel::where('status', 1)->orderBy('name', 'ASC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = CityModel::where('id', $id)->orderBy('name', 'ASC')->first();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'name' => 'required|string',
            'country_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "name" => $post['name'],
            "country_id" => $post['country_id'],
            "status" => 1,
        ];
        CityModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "name" => $post['name'],
            "country_id" => $post['country_id'],
        ];
        $row = CityModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = CityModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}