<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    protected $table = "reviews";
    protected $fillable = ["user_id", "ebook_id", "comment", "rating", "review_id", "like", "dislike", "status"];
}