<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PageModel;
use App\FollowerModel;
use File;


class PageController extends Controller
{
    public function adminView()
    {
        $data = PageModel::with(['user', 'category'])->where('id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = PageModel::with(['user', 'category'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = PageModel::with(['user', 'category'])->where('id', $id)->first();
        $following = FollowerModel::where(['user_id' => $id, 'is_page' => 1])->count();
        $followers = FollowerModel::where(['profile_id' => $id, 'is_page' => 1])->count();
        return response()->json(['pageInfo' => $data, 'following' => $following, 'followers' => $followers], 200);
    }

    public function viewByJoinId($id)
    {
        $data = PageModel::with(['user', 'category'])->where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "name" => $post['name'],
            "email" => $post['email'],
            "contact" => $post['contact'],
            "category_id" => $post['category_id'],
            "bio" => $post['bio'],
        ];

        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/'.$name);
            $imgFile = 'images/'.$name;
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
            "category_id" => $post['category_id'],
            "bio" => $post['bio'],
        ];

        $row = PageModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }

    public function updatePhoto(Request $request, $id)
    {
        $post = $request->all();
        $row = PageModel::findOrFail($id);
        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/'.$name);
            $imgFile = 'images/'.$name;
        }
        $image_path = $row->image;
        if(File::exists($image_path) && @$imgFile)
        {
            File::delete($image_path);
        }
        $row->update(['image' => $imgFile]);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = PageModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
