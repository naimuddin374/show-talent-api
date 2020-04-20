<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreferenceModel extends Model
{
    protected $table = "preferences";
    protected $fillable = ["user_id", "category_id"];
}
