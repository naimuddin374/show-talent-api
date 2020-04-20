<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCatModel extends Model
{
    protected $table = "pdt_cats";
    protected $fillable = ["name"];
}