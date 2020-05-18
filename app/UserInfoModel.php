<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfoModel extends Model
{
    protected $table = 'user_infos';
    protected $fillable = ['user_id', 'country_id', 'city_id', 'address', 'dob', 'gender', 'bio'];
}
