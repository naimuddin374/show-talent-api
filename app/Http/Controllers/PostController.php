<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostModel;
use File;


// auth()->user()


class PostController extends Controller
{
    public function adminView()
    {
        $data = PostModel::leftJoin('users', 'users.id', '=', 'posts.user_id')->select('users.name as user_name','posts.*')->orderBy('posts.id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PostModel::leftJoin('users', 'users.id', '=', 'posts.user_id')->select('users.name as user_name','posts.*')->orderBy('posts.id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = PostModel::leftJoin('users', 'users.id', '=', 'posts.user_id')->select('users.name as user_name','posts.*')->where('posts.id', $id)->orderBy('posts.id', 'DESC')->first();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = PostModel::leftJoin('users', 'users.id', '=', 'posts.user_id')->select('users.name as user_name','posts.*')->where('users.id', $id)->orderBy('posts.id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function approve($id)
    {
        $data = PostModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = PostModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
    }


    public function postShowByType($type)
    {
        $data = PostModel::leftJoin('users', 'users.id', '=', 'posts.user_id')
                ->leftJoin('categories', 'categories.id', '=', 'posts.category_id')
                ->where('posts.type', $type)
                ->select('users.name as user_name', 'categories.name as cat_name','posts.*')
                ->orderBy('posts.id', 'DESC')
                ->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'type' => 'required|numeric',
            'category_id' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "status" => $post['status'],
            "user_id" => $auth['id'],
            "type" => $post['type'],
            "category_id" => $post['category_id'],
            "page_id" => $post['page_id'],
            "title" => $post['title'],
            "description" => $post['description'],
            "newslink" => $post['newslink'],
            "video" => $post['video'],
        ];


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['image'] = 'storage/images/'.$name;
        }


        PostModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "status" => $post['status'],
            "type" => $post['type'],
            "category_id" => $post['category_id'],
            "title" => $post['title'],
            "description" => $post['description'],
            "newslink" => $post['newslink'],
            "video" => $post['video'],
        ];

        $row = PostModel::findOrFail($id);


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
        $row = PostModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
