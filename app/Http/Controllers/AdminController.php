<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PostModel;
use App\PostCommentModel;
use App\EbookModel;
use App\CommentModel;
use App\ChapterModel;
use App\ClassifiedModel;
use App\UserModel;
use App\PageModel;

class AdminController extends Controller
{
    public function countPending()
    {
        $post = PostModel::where('status', 0)->select('type')->get();
        $postComment = PostCommentModel::where('status', 0)->count();
        $ebook = EbookModel::where('status', 0)->count();
        $ebookComment = CommentModel::where('status', 0)->count();
        $chapter = ChapterModel::where('status', 0)->count();
        $classified = ClassifiedModel::where('status', 0)->count();
        $user = UserModel::where('status', 0)->count();
        $page = PageModel::where('status', 0)->count();
        return response()->json(['post'=>$post, 'postComment'=>$postComment, 'ebook'=>$ebook, 'ebookComment'=>$ebookComment, 'chapter'=>$chapter, 'user'=>$user, 'page'=>$page, 'classified'=>$classified], 200);
    }
}
