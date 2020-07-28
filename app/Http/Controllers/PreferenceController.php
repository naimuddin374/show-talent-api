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
        $data = PreferenceModel::with(['user', 'category'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = PreferenceModel::with(['user', 'category'])->where('id', $id)->orderBy('id', 'DESC')->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = PreferenceModel::with(['user', 'category'])->where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();

        PreferenceModel::where(['user_id' => $id])->delete();

        foreach($post['category_id'] as $item){
            PreferenceModel::create(['user_id' => $id, 'category_id' => $item]);
        }
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = PreferenceModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}