<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EduInfoModel extends Model
{
    protected $table = 'edu_infos';
    protected $fillable = ['user_id', 'degree', 'institute', 'start_date', 'end_date', 'passing_year', 'status'];
}
