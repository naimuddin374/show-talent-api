<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostModel;
use App\PostLikeModel;
use File;


// auth()->user()


class PostController extends Controller
{
    public function adminView()
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments'])->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments'])->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments'])->where(['id'=> $id])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user'])->where(['user_id'=> $id, 'page_id' => 0])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getPagePost($id)
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments'])
                ->where(['posts.page_id' => $id])
                ->orderBy('id', 'desc')
                ->get();
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
        ];
        if($data['type'] == 4){
            $data['description'] = strip_tags($data['description']);
        }

       if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/'.$name);
            $data['image'] = 'images/'.$name;
        }

        PostModel::create($data);
        return response()->json(["message" => "Created successful.", 'post'=> $data], 201);
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
        ];
        if($data['type'] == 4){
            $data['description'] = strip_tags($data['description']);
        }
        $row = PostModel::findOrFail($id);

        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/'.$name);
            $data['image'] = 'images/'.$name;
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

        $image_path = $row->image;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }

        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}