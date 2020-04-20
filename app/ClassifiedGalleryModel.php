<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassifiedGalleryModel extends Model
{
    protected $table = "classified_galleries";
    protected $fillable = ["post_id", "image"];
}
