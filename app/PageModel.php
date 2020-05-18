<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $fillable = ['user_id', 'name', 'email', 'contact', 'image', 'category_id', 'creation_date', 'expiration_date', 'view', 'follow', 'status'];
}
