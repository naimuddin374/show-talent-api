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
        $data = ClassifiedModel::orderBy('id', 'DESC')->where(['status' => 1])->get();
        // $data = ClassifiedModel::where('status', 1)->orderBy('id', 'DESC')->get();
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
            "reject_note" => null
        ];
        $row = ClassifiedModel::findOrFail($id);
        $row->update($data);

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
        ];
        $row = ClassifiedModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Rejected successfully."], 201);
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
            "email" => $post['email'],
            "title" => $post['title'],
            "description" => $post['description'],
            "price" => $post['price'],
            "currency" => $post['currency'],
            "address" => $post['address'],
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
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "type" => $post['type'],
            "contact" => $post['contact'],
            "email" => $post['email'],
            "title" => $post['title'],
            "description" => $post['description'],
            "price" => $post['price'],
            "currency" => $post['currency'],
            "address" => $post['address'],
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
        return response()->json(["message" => "Updated successful."], 201);
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
