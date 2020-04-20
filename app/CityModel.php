<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    protected $table = "cities";
    protected $fillable = ["name", "country_id", "status"];
}
