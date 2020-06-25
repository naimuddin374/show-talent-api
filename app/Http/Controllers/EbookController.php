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
        $data = EbookModel::with(['user', 'page', 'chapter', 'category'])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments.likes'])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments'])->where(['id' => $id])->orderBy('id', 'desc')->first();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = EbookModel::with(['user', 'page', 'chapter', 'category', 'comments'])->where(['user_id' => $id])->orderBy('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function approve($id)
    {
        $data = EbookModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = EbookModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
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