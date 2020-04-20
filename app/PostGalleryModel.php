<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostGalleryModel extends Model
{
    protected $table = "post_galleries";
    protected $fillable = ["post_id", "image"];
}