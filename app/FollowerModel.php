<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowerModel extends Model
{
    protected $table = 'followers';
    protected $fillable = ['profile_id', 'user_id', 'is_page'];
}
