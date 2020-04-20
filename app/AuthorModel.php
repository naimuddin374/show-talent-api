<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorModel extends Model
{
    protected $table = "authors";
    protected $fillable = ["name", "bio"];
}
