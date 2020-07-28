<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EbookModel;
use App\ChapterModel;
use File;


class EbookController extends Controller
{
    public function adminView()
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category'])->where('status', '!=',  5)->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments.likes'])->where(['status' => 1])->orderBy('id', 'desc')->get();

    //    $data = EbookModel::with(['user', 'page', 'category', 'comments.likes'])->with(['chapter' => function($q) {
    //         $q->where('status', '=', 1);
    //     }])->where(['status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments.user', 'comments.likes'])->where(['id' => $id])->orderBy('id', 'desc')->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments.likes'])->where(['user_id' => $id, 'status' => 1])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function getByPage($id)
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments.likes'])->where(['page_id' => $id, 'status' => 1])->orderBy('id', 'desc')->get();
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
            'is_unread' => 1
        ];
        $row = EbookModel::findOrFail($id);
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
            'is_unread' => 1
        ];
        $row = EbookModel::findOrFail($id);
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
            'is_unread' => 1
        ];
        $row = EbookModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        EbookModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
    }



    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'name' => 'required',
            'summary' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "category_id" => $post['category_id'],
            "name" => $post['name'],
            "page_id" => $post['page_id'],
            "author_name" => $post['author_name'],
            "publication_date" => $post['publication_date'],
            "preface" => $post['preface'],
            "summary" => $post['summary'],
            "author_summary" => $post['author_summary'],
            "preface" => $post['preface'],
            'status' => 5
        ];
        $id = EbookModel::create($data)->id;
        return response()->json(["message" => "Created successful.", 'id' => $id], 201);
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "name" => $post['name'],
            "category_id" => $post['category_id'],
            "author_name" => $post['author_name'],
            "publication_date" => $post['publication_date'],
            "preface" => $post['preface'],
            "summary" => $post['summary'],
            "author_summary" => $post['author_summary'],
            "preface" => $post['preface'],
            'status' => 0
        ];

        $row = EbookModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = EbookModel::findOrFail($id);

        $image_path = $row->front_image;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }

        $image_path = $row->back_image;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }

        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }

    public function uploadCoverPhoto(Request $request, $id)
    {
        $post = $request->all();
        $data = [];

        if(@$post['front_image'])
        {
            $image = $post['front_image'];
            $name = time().'_front.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/ebook/'.$name);
            $data['front_image'] = 'images/ebook/'.$name;
        }

        if(@$post['back_image'])
        {
            $image = $post['back_image'];
            $name = time().'_back.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save('images/ebook/'.$name);
            $data['back_image'] = 'images/ebook/'.$name;
        }

        $row = EbookModel::findOrFail($id);
        $image_path = $row->front_image;
        if(File::exists($image_path) && @$data['front_image'])
        {
            File::delete($image_path);
        }

        $row = EbookModel::findOrFail($id);
        $image_path = $row->back_image;
        if(File::exists($image_path) && @$data['back_image'])
        {
            File::delete($image_path);
        }

        $row->update($data);
        return response()->json(["message" => "Created successful."], 201);
    }
}