<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    protected $table = "posts";
    protected $fillable = ["user_id", "type", "category_id", "page_id", "title", "description", "newslink", "video", "image", "status"];
}
