<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    protected $table = "comments";
    protected $fillable = ["user_id", "post_id", "comment", "comment_id", "status"];
}