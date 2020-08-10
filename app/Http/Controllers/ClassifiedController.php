<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ClassifiedModel;
use App\ClassifiedGalleryModel;
use File;


class ClassifiedController extends Controller
{

    public function adminView()
    {
        $data = ClassifiedModel::with(['user', 'category', 'page'])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = ClassifiedModel::with(['city'])->orderBy('id', 'DESC')->where(['status' => 1])->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = ClassifiedModel::with(['user', 'category', 'page'])->where(['id' => $id])->orderBy('id', 'desc')->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = ClassifiedModel::with(['user', 'category', 'page'])->where(['user_id' => $id, 'page_id' => 0, 'status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getPagePost($id)
    {
        $data = ClassifiedModel::with(['user', 'category', 'page'])->where(['page_id'=> $id, 'status' => 1])->orderBy('id', 'desc')->get();
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
            'created_at' => date('Y-m-d H:i:s')
        ];
        $row = ClassifiedModel::findOrFail($id);
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
            "admin_id" => $auth['id'],
            "reject_note" => $post['reject_note'],
            'is_unread' => 1
        ];
        $row = ClassifiedModel::findOrFail($id);
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
        $row = ClassifiedModel::findOrFail($id);
        removeRewardPoint($row->user_id, $row->page_id, $row->points);
        $row->update($data);

        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        ClassifiedModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
    }

    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'type' => 'required|numeric',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "page_id" => $post['page_id'],
            "type" => $post['type'],
            "contact" => $post['contact'],
            "title" => $post['title'],
            "description" => $post['description'],
            "price" => $post['price'],
            'created_at' => date('Y-m-d H:i:s')
        ];


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'_thm_.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/classified/'.$name);
            $data['image'] = 'images/classified/'.$name;
        }

        $id = ClassifiedModel::create($data)->id;

        if(@$post['galleries'] && $id)
        {
            foreach ($post['galleries'] as $key => $value) {
                $image = $value;
                $name = time().$key.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                \Image::make($image)->save('images/classified/'.$name);
                ClassifiedGalleryModel::create(['classified_id' => $id, 'image' => 'images/classified/'.$name]);
            }
        }
        return response()->json(["message" => "Your classified has been submitted successfully, it's pending for admin approval."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "page_id" => $post['page_id'],
            "type" => $post['type'],
            "contact" => $post['contact'],
            "title" => $post['title'],
            "description" => $post['description'],
            "price" => $post['price'],
            'created_at' => date('Y-m-d H:i:s'),
            "status" => 0
        ];

        $row = ClassifiedModel::findOrFail($id);

        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'_thm_.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/classified/'.$name);
            $data['image'] = 'images/classified/'.$name;
        }

        $image_path = $row->image;
        if(File::exists($image_path) && @$data['image'])
        {
            File::delete($image_path);
        }
        $row->update($data);
        return response()->json(["message" => "Your classified has been updated successfully, it's pending for admin approval."], 201);
    }

    public function delete($id)
    {
        $row = ClassifiedModel::findOrFail($id);
        $row->delete();

        $image_path = $row->image;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
        return response()->json(["message" => "Deleted successful."], 201);
    }
}