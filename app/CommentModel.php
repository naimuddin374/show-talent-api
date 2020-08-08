<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\CommentLikeModel;


class CommentModel extends Model
{
    protected $table = 'comments';
    protected $fillable = ['user_id', 'ebook_id', 'rating', 'comment', 'status', 'reject_note', 'admin_id', 'is_unread', 'created_at'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function likes(){
        return $this->hasMany(CommentLikeModel::class, 'comment_id');
    }
}