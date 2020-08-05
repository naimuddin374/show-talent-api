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
        $data = PageModel::with(['user', 'category', 'follower'])->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = PageModel::with(['user', 'category', 'follower'])->where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = PageModel::with(['user', 'category', 'follower'])->where('id', $id)->first();
        // $following = FollowerModel::where(['user_id' => $id, 'is_page' => 1])->count();
        // $followers = FollowerModel::where(['profile_id' => $id, 'is_page' => 1])->count();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = PageModel::with(['user', 'category'])->where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function approve(Request $request, $id)
    {
        $auth = auth()->user();
        $post = $request->all();
        $data = [
            "status" => 1,
            "admin_id" => $auth['id'],
            "reject_note" => null,
            'is_unread' => 1,
            'points' => $post['points'],
        ];
        $row = PageModel::findOrFail($id);
        $row->update($data);
        addRewardPoint($row->user_id, @$post['points']);


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
            'is_unread' => 1
        ];
        $row = PageModel::findOrFail($id);
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
            "reject_note" => null,
            'is_unread' => 1,
            'points' => 0,
        ];
        $row = PageModel::findOrFail($id);
        $row->update($data);
        removeRewardPoint($row->user_id, $row->points);
        
        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        PageModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
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
        $userId = auth()->user()['id'];
        $sel_page = PageModel::where(['name' => $post['name'], 'user_id' => $userId])->first();
        if($sel_page){
            return response()->json(["message" => "Page already exist."], 406);
        }
        
        $data = [
            "user_id" => $userId,
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
        $id = PageModel::create($data)->id;
        return response()->json(["message" => "Created successful.", "insertId" => $id], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
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

        $image_path = $row->image;
        if(File::exists($image_path) && @$imgFile)
        {
            File::delete($image_path);
        }
        
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }

    public function deleteProfilePic($id)
    {
        $row = PageModel::findOrFail($id);

        $image_path = $row->image;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
        
        $row->update(['image' => null]);
        return response()->json(["message" => "Deleted successful."], 201);
    }
}