<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ReviewModel;


class ReviewController extends Controller
{
    public function adminView()
    {
        $data = ReviewModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = ReviewModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }


    public function detail($id)
    {
        $data = ReviewModel::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = ReviewModel::where('ebook_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function approve($id)
    {
        $data = ReviewModel::where('id', $id)->update(['status' => 1]);
        return response()->json(["message" => "Approve successful."], 201);
    }
    public function reject($id)
    {
        $data = ReviewModel::where('id', $id)->update(['status' => 2]);
        return response()->json(["message" => "Rejected successful."], 201);
    }


    public function store(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post, [
            'user_id' => 'required',
            'ebook_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
        $data = [
            "user_id" => $post['user_id'],
            "ebook_id" => $post['ebook_id'],
            "comment" => $post['comment'],
            "rating" => $post['rating'],
            "review_id" => $post['review_id'],
        ];
        ReviewModel::create($data)->id;
        return response()->json(["message" => "Created successful."], 201);
    }


    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "ebook_id" => $post['ebook_id'],
            "comment" => $post['comment'],
            "rating" => $post['rating'],
            "review_id" => $post['review_id'],
        ];
        $row = ReviewModel::findOrFail($id);
        $row->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = ReviewModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }
}
