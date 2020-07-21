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
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user', 'moderator'])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user'])->where(['status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user', 'moderator'])->where(['id'=> $id])->orderBy('id', 'desc')->first();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user'])->where(['user_id'=> $id, 'page_id' => 0, 'status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getByPage($id)
    {
        $data = PostModel::with(['user', 'category', 'page', 'likes', 'comments.user'])->where(['page_id' => $id, 'status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function approve(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 1,
            "admin_id" => $auth['id'],
            "reject_note" => null
        ];
        $row = PostModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 2,
            "reject_note" => $post['reject_note'],
            "admin_id" => $auth['id'],
        ];
        $row = PostModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Rejected successfully."], 201);
    }
    public function unpublish(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 3,
            "admin_id" => $auth['id'],
            "reject_note" => null
        ];
        $row = PostModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function editorPickHandle(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "is_editor" => $post['is_editor'],
        ];
        $row = PostModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Editor update successful."], 201);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'type' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "type" => $post['type'],
            "category_id" => $post['category_id'],
            "page_id" => $post['page_id'],
            "title" => $post['title'],
            "description" => $post['description'],
            "newslink" => @$post['newslink'],
            "video" => @$post['video'],
        ];

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
            "type" => $post['type'],
            "category_id" => $post['category_id'],
            "title" => $post['title'],
            "description" => $post['description'],
            "newslink" => @$post['newslink'],
            "video" => @$post['video'],
        ];
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
