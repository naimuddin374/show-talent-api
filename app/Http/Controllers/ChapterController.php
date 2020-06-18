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

    public function detail($id)
    {
        $data = ChapterModel::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = ChapterModel::where('ebook_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function approve($id)
    {
        $data = ChapterModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = ChapterModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'ebook_id' => 'required|numeric',
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "ebook_id" => $post['ebook_id'],
            "sequence" => $post['sequence'],
            "name" => $post['name'],
            "description" => $post['description'],
        ];
        ChapterModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
             "sequence" => $post['sequence'],
            "name" => $post['name'],
            "description" => $post['description'],
        ];
        $row = ChapterModel::findOrFail($id);
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