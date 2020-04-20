<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ClassifiedModel;


class ClassifiedController extends Controller
{
    public function adminView()
    {
        $data = ClassifiedModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = ClassifiedModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required|numeric',
            'type' => 'required|numeric',
            'status' => 'required|numeric',
            'contact' => 'numeric',
            'city_id' => 'numeric',
            'email' => 'email',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "category_id" => $post['category_id'],
            "contact" => $post['contact'],
            "email" => $post['email'],
            "title" => $post['title'],
            "description" => $post['description'],
            "price" => $post['price'],
            "city_id" => $post['city_id'],
            "address" => $post['address'],
            "status" => $post['status'],
        ];


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['image'] = 'storage/images/'.$name;
        }


        ClassifiedModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "category_id" => $post['category_id'],
            "contact" => $post['contact'],
            "email" => $post['email'],
            "title" => $post['title'],
            "description" => $post['description'],
            "price" => $post['price'],
            "city_id" => $post['city_id'],
            "address" => $post['address'],
            "status" => $post['status'],
        ];
        $row = ClassifiedModel::findOrFail($id);


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['image'] = 'storage/images/'.$name;
        }
        $image_path = $row->image;
        if(File::exists($image_path) && @$data['image'])
        {
            File::delete($image_path);
        }


        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = ClassifiedModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
