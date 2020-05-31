<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLikeModel extends Model
{
    protected $table = 'post_likes';
    protected $fillable = ['user_id', 'post_id'];
}
