<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = "users";
    protected $fillable = ["full_name", "name", "contact", "email", "password", "image", "type", "status"];
}