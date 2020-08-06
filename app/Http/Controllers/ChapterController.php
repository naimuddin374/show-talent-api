<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ChapterModel;
use App\EbookModel;
use File;
use PDF;

class ChapterController extends Controller
{
    public function adminView()
    {
        $data = ChapterModel::orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function view()
    {
        $data = ChapterModel::where('status', 1)->orderBy('sequence', 'ASC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = ChapterModel::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function viewByJoinId($id)
    {
        $data = ChapterModel::where('ebook_id', $id)->orderBy('sequence', 'ASC')->get();
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
        ];
        $row = ChapterModel::findOrFail($id);
        $row->update($data);
        $book = EbookModel::where('ebook_id', $row->ebook_id)->first();
        addRewardPoint($book->user_id, $book->page_id, @$post['points']);
        
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
            'is_unread' => 1,
        ];
        $row = ChapterModel::findOrFail($id);
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
        $row = ChapterModel::findOrFail($id);
        $row->update($data);
        $book = EbookModel::where('ebook_id', $row->ebook_id)->first();
        removeRewardPoint($book->user_id, $book->page_id, $row->points);

        return response()->json(["message" => "Unpublish successful."], 201);
    }
    public function readAll()
    {
        ChapterModel::where(['is_unread' => 1])->update(['is_unread' => 0]);
        return response()->json(["message" => "Read successful."], 201);
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
        $id = ChapterModel::create($data)->id;
        if($id && @$post['isDraft'] == 1){
            EbookModel::where(['id' => $post['ebook_id']])->update(['status' => 5]);
            return response()->json(["message" => "Save as draft successfully."], 201);
        }else{
            EbookModel::where(['id' => $post['ebook_id']])->update(['status' => 0]);
            return response()->json(["message" => "Submit for review successfully."], 201);
        }
        return response()->json(["message" => "Submit for review successfully."], 201);
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
        // $this->makePDF($row->ebook_id);

        return response()->json(["message" => "Updated successful."], 201);
    }


    public function delete($id)
    {
        $row = ChapterModel::findOrFail($id);
        $row->delete();
        return response()->json(["message" => "Deleted successful."], 201);
    }

    public function makePDF($id)
    {
        $data = ChapterModel::where(['ebook_id' => $id])->orderBy('sequence', 'ASC')->get();
        $pdf = PDF::loadView('ebook-pdf', ['data' => $data]);
        $content = $pdf->output();
        $x= public_path("pdf/book-{$id}.pdf");
        file_put_contents($x, $content);
        // return $pdf->download('medium.pdf');
    }
}