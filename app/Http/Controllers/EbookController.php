<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EbookModel;
use File;

class EbookController extends Controller
{
    public function adminView()
    {
        $data = EbookModel::leftJoin('users', 'users.id', '=', 'ebooks.user_id')
                ->leftJoin('categories', 'categories.id', '=', 'ebooks.category_id')
                ->leftJoin('authors', 'authors.id', '=', 'ebooks.author_id')
                ->select('users.name as user_name', 'categories.name as cat_name', 'authors.name as author_name','ebooks.*')
                ->orderBy('ebooks.id', 'DESC')
                ->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = EbookModel::leftJoin('users', 'users.id', '=', 'ebooks.user_id')
                ->leftJoin('categories', 'categories.id', '=', 'ebooks.category_id')
                ->leftJoin('authors', 'authors.id', '=', 'ebooks.author_id')
                ->select('users.name as user_name', 'categories.name as cat_name', 'authors.name as author_name','ebooks.*')
                ->where('ebooks.status', 1)
                ->orderBy('ebooks.id', 'DESC')
                ->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = EbookModel::with(['user', 'page'])->where(['id' => $id])->orderBy('id', 'desc')->first();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = EbookModel::leftJoin('users', 'users.id', '=', 'ebooks.user_id')
                ->leftJoin('categories', 'categories.id', '=', 'ebooks.category_id')
                ->leftJoin('authors', 'authors.id', '=', 'ebooks.author_id')
                ->select('users.name as user_name', 'categories.name as cat_name', 'authors.name as author_name','ebooks.*')
                ->where('ebooks.user_id', $id)
                ->orderBy('ebooks.id', 'DESC')
                ->get();
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
            'summery' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $auth = auth()->user();
        $data = [
            "user_id" => $auth['id'],
            "name" => $post['name'],
            "author_name" => $post['author_name'],
            "publication_date" => $post['publication_date'],
            "preface" => $post['preface'],
            "summery" => $post['summery'],
            "author_summery" => $post['author_summery'],
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
            "author_name" => $post['author_name'],
            "publication_date" => $post['publication_date'],
            "preface" => $post['preface'],
            "summery" => $post['summery'],
            "author_summery" => $post['author_summery'],
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
