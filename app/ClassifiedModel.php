<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassifiedModel extends Model
{
    protected $table = 'classifieds';
    protected $fillable = ['user_id', 'type', 'category_id', 'contact', 'email', 'title', 'description', 'image', 'price', 'city_id', 'address', 'status'];
}
