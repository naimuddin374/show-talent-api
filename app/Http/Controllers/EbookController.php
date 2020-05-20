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
        $data = EbookModel::leftJoin('users', 'users.id', '=', 'ebooks.user_id')
                ->leftJoin('categories', 'categories.id', '=', 'ebooks.category_id')
                ->leftJoin('authors', 'authors.id', '=', 'ebooks.author_id')
                ->select('users.name as user_name', 'categories.name as cat_name', 'authors.name as author_name','ebooks.*')
                ->where('ebooks.id', $id)
                ->first();
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
            'user_id' => 'required',
            'category_id' => 'required|numeric',
            'author_id' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "category_id" => $post['category_id'],
            "language" => $post['language'],
            "author_id" => $post['author_id'],
            "publication_date" => $post['publication_date'],
            "ebook_summery" => $post['ebook_summery'],
            "author_summery" => $post['author_summery'],
            "number_of_chapter" => $post['number_of_chapter'],
            "preface" => $post['preface'],
            "price" => $post['price'],
        ];


        if(@$post['font_image'])
        {
            $image = $post['font_image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['font_image'] = 'storage/images/'.$name;
        }

        if(@$post['back_image'])
        {
            $image = $post['back_image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['back_image'] = 'storage/images/'.$name;
        }


        EbookModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "category_id" => $post['category_id'],
            "title" => $post['title'],
            "language" => $post['language'],
            "publication_date" => $post['publication_date'],
            "ebook_summery" => $post['ebook_summery'],
            "author_summery" => $post['author_summery'],
            "preface" => $post['preface'],
            "price" => $post['price'],
            "status" => $post['status'],
        ];

        $row = EbookModel::findOrFail($id);


        if(@$post['font_image'])
        {
            $image = $post['font_image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['font_image'] = 'storage/images/'.$name;
        }
        $image_path = $row->font_image;
        if(File::exists($image_path) && @$data['font_image'])
        {
            File::delete($image_path);
        }

        if(@$post['back_image'])
        {
            $image = $post['back_image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['back_image'] = 'storage/images/'.$name;
        }
        $image_path = $row->back_image;
        if(File::exists($image_path) && @$data['back_image'])
        {
            File::delete($image_path);
        }

        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = EbookModel::findOrFail($id);

        $image_path = $row->font_image;
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
}
