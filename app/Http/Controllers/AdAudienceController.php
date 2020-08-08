<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AdAudienceModel;


class AdAudienceController extends Controller
{

    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'ad_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "ad_id" => $post['ad_id'],
            "gender" => $post['gender'],
            "age_start" => $post['age_start'],
            "age_end" => $post['age_end'],
        ];
        if($post['locations']){
            $countries = [];
            $cities = [];
            foreach($post['locations'] as $location){
                array_push($countries, $location['country_id']);
                array_push($cities, $location['city_id']);
            }
            $data['countries'] = implode($countries, ',');
            $data['cities'] = implode($cities, ',');
        }
        AdAudienceModel::create($data);
        return response()->json(["message" => "Save Successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "gender" => $post['gender'],
            "age_start" => $post['age_start'],
            "age_end" => $post['age_end'],
        ];
        
        if($post['locations']){
            $countries = [];
            $cities = [];
            foreach($post['locations'] as $location){
                array_push($countries, $location['country_id']);
                array_push($cities, $location['city_id']);
            }
            $data['countries'] = implode($countries, ',');
            $data['cities'] = implode($cities, ',');
        }

        $row = AdAudienceModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated Successful."], 201);
    }


    public function delete($id)
    {
        $row = AdAudienceModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}