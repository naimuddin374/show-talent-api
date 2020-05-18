<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkExpModel extends Model
{
    protected $table = 'work_exps';
    protected $fillable = ['user_id', 'job_title', 'company', 'start_date', 'end_date', 'status'];
}
