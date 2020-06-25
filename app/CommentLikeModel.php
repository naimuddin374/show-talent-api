<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CommentModel;
use App\UserModel;

class CommentLikeModel extends Model
{
    protected $table = 'comment_likes';
    protected $fillable = ['user_id', 'comment_id', 'type'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function comment(){
        return $this->belongsTo(CommentModel::class);
    }
}
