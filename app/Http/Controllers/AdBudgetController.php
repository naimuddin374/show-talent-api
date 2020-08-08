<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AdModel;
use App\AdBudgetModel;

class AdBudgetController extends Controller
{
    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'ad_id' => 'required|numeric',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "ad_id" => $post['ad_id'],
            "amount" => $post['amount'],
            "currency" => $post['currency'],
        ];
        AdBudgetModel::create($data);
        if(@$post['website']){
            AdModel::where('id', $post['ad_id'])->update(['website' => $post['website']]);
        }
        return response()->json(["message" => "Your ad post has been submitted successfully, it's pending for admin approval."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "ad_id" => $post['ad_id'],
            "amount" => $post['amount'],
            "currency" => $post['currency'],
        ];
        $row = AdBudgetModel::findOrFail($id);
        $row->update($data);
        if(@$post['website']){
            AdModel::where('id', $post['ad_id'])->update(['website' => $post['website']]);
        }
        return response()->json(["message" => "Your ad post has been updated successfully, it's pending for admin approval."], 201);
    }


    public function delete($id)
    {
        $row = AdBudgetModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}