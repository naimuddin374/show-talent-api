<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UserModel;
use App\UserInfoModel;
use App\ClassifiedModel;
use App\PostModel;
use App\EbookModel;
use App\ChapterModel;
use App\CommentModel;
use App\PostCommentModel;
use App\PageModel;
use File;


class ProfileController extends Controller
{
    public function getAllUnread(){
        $userId = auth()->user()['id'];
        $classified = ClassifiedModel::where(['user_id' => $userId])->whereNotIn('status', [0])->orderBy('id', 'DESC')->get();
        $post = PostModel::where(['user_id' => $userId])->whereNotIn('status', [0])->orderBy('id', 'DESC')->get();
        $ebook = EbookModel::where(['user_id' => $userId])->whereNotIn('status', [0])->orderBy('id', 'DESC')->get();
        $comment = CommentModel::where(['user_id' => $userId])->whereNotIn('status', [0])->orderBy('id', 'DESC')->get();
        $postComment = PostCommentModel::where(['user_id' => $userId])->whereNotIn('status', [0])->orderBy('id', 'DESC')->get();
        $page = PageModel::where(['user_id' => $userId])->whereNotIn('status', [0])->orderBy('id', 'DESC')->get();

        $res = array(
            'classified' => $classified,
            'post' => $post,
            'ebook' => $ebook,
            'comment' => $comment,
            'postComment' => $postComment,
            'page' => $page,
        );

        return response()->json($res, 200);
    }

    public function changeAllRead(){
        $userId = auth()->user()['id'];
        ClassifiedModel::where(['user_id' => $userId, 'is_unread' => 1])->update(['is_unread' => 0]);
        PostModel::where(['user_id' => $userId, 'is_unread' => 1])->update(['is_unread' => 0]);
        EbookModel::where(['user_id' => $userId, 'is_unread' => 1])->update(['is_unread' => 0]);
        CommentModel::where(['user_id' => $userId, 'is_unread' => 1])->update(['is_unread' => 0]);
        PostCommentModel::where(['user_id' => $userId, 'is_unread' => 1])->update(['is_unread' => 0]);
        PageModel::where(['user_id' => $userId, 'is_unread' => 1])->update(['is_unread' => 0]);

        return response()->json(['message' => 'Make all read'], 200);
    }

     public function getAuthUserInfo(){
         $auth = auth()->user();
         $data = UserModel::leftJoin('user_infos', 'user_infos.user_id', '=', 'users.id')->select('user_infos.*','users.*')->orderBy('users.id', 'DESC')->where('users.id', $auth['id'])->first();
        return response()->json($data, 200);
    }


    public function view()
    {
        $data = UserModel::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $data = UserModel::where('id', $id)->first();
        return response()->json($data, 200);
    }


    public function viewByJoinId($id)
    {
        $data = UserModel::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $post = $request->all();
        $data = [
            "full_name" => $post['full_name'],
            "name" => $post['name'],
            "contact" => $post['contact'],
            "email" => $post['email'],
        ];
        $row = UserModel::findOrFail($id);
        $row->update($data);


        $data = [
            "country_id" => $post['country_id'],
            "city_id" => $post['city_id'],
            "address" => $post['address'],
            "dob" => $post['dob'],
            "gender" => $post['gender'],
            "bio" => $post['bio'],
        ];
        UserInfoModel::where(['user_id' => $id])->update($data);
        return response()->json(["message" => "Updated successful."], 201);
    }


    public function updateProfilePhoto(Request $request){
        $post = $request->all();
        $image = $post['image'];
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        // \Image::make($image)->save(public_path('images/').$name);
        \Image::make($image)->save('images/'.$name);
        $imgFile = 'images/'.$name;

        $auth = auth()->user();
        $row = UserModel::findOrFail($auth['id']);

        $image_path = $row->image;
        if(File::exists($image_path) && @$imgFile)
        {
            File::delete($image_path);
        }
        $row->update(['image' => $imgFile]);
        return response()->json(["message" => "Updated successful.", 'image' => $imgFile], 201);
    }

    public function deleteProfilePic($id)
    {
        $row = UserModel::findOrFail($id);

        if(File::exists($row->image))
        {
            File::delete($row->image);
        }
        
        $row->update(['image' => null]);
        return response()->json(["message" => "Deleted successful."], 201);
    }

}