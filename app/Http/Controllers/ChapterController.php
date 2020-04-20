<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ChapterModel;
use File;

class ChapterController extends Controller
{
    public function adminView()
    {
        $data = ChapterModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = ChapterModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'ebook_id' => 'required|numeric',
            'sequence' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "ebook_id" => $post['ebook_id'],
            "sequence" => $post['sequence'],
            "status" => $post['status'],
        ];


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['image'] = 'storage/images/'.$name;
        }


        ChapterModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "ebook_id" => $post['ebook_id'],
            "sequence" => $post['sequence'],
            "status" => $post['status'],
            "reject_note" => $post['reject_note'],
            "reopen_note" => $post['reopen_note'],
        ];
        $row = ChapterModel::findOrFail($id);


        if(@$post['image'])
        {
            $image = $post['image'];
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(storage_path('app/public/images/').$name);
            $data['image'] = 'storage/images/'.$name;
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
        $row = ChapterModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}