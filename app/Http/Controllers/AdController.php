<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AdModel;
use File;


class AdController extends Controller
{
    public function adminView()
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->where(['status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->where(['id'=> $id])->orderBy('id', 'desc')->first();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->where(['user_id'=> $id, 'page_id' => 0, 'status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getByPage($id)
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->where(['page_id' => $id, 'status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getUserAdList($id)
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->where(['user_id'=> $id, 'page_id' => 0])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function viewPageAdList($id)
    {
        $data = AdModel::with(['user', 'page', 'audience', 'budget'])->where(['page_id' => $id])->orderBy('id', 'desc')->get();
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
            "reopen_note" => null,
            'is_unread' => 1,
            'points' => $post['points'],
        ];
        $row = AdModel::findOrFail($id);
        $row->update($data);
        addRewardPoint($row->user_id, $row->page_id, @$post['points']);

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
        $row = AdModel::findOrFail($id);
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
        $row = AdModel::findOrFail($id);
        removeRewardPoint($row->user_id, $row->page_id, $row->points);
        $row->update($data);

        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        AdModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
    }
    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'category_id' => 'required|numeric',
            'placement' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "category_id" => $post['category_id'],
            "page_id" => $post['page_id'],
            "placement" => $post['placement'],
        ];

       if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/'.$name);
            $data['image'] = 'images/'.$name;
        }

        $id = AdModel::create($data)->id;
        return response()->json(["message" => "Select your targeted audience.", "adId" => $id], 201);
        // return response()->json(["message" => "Your ad post has been submitted successfully, it's pending for admin approval.", 'post'=> $data], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "category_id" => $post['category_id'],
            "placement" => $post['placement'],
            'status' => 0
        ];
        $row = AdModel::findOrFail($id);

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
        $row = AdModel::findOrFail($id);

        $image_path = $row->image;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }

        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}