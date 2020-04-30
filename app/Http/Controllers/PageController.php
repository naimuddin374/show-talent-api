<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PageModel;
use File;


class PageController extends Controller
{
    public function adminView()
    {
        $data = PageModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PageModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function getByUserId()
    {
        $data = PageModel::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required',
            'name' => 'required',
            'status' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "status" => $post['status'],
            "user_id" => $post['user_id'],
            "name" => $post['name'],
            "email" => $post['email'],
            "contact" => $post['contact'],
            "creation_date" => $post['creation_date'],
            "expiration_date" => $post['expiration_date'],
        ];


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['image'] = 'storage/images/'.$name;
        }


        PageModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "status" => $post['status'],
            "name" => $post['name'],
            "email" => $post['email'],
            "contact" => $post['contact'],
            "creation_date" => $post['creation_date'],
            "expiration_date" => $post['expiration_date'],
        ];

        $row = PageModel::findOrFail($id);


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
        $row = PageModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
