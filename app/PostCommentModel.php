<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;

class PostCommentModel extends Model
{
    protected $table = 'post_comments';
    protected $fillable = ['user_id', 'post_id', 'comment', 'status'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
}
