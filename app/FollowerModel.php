<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;

class FollowerModel extends Model
{
    protected $table = 'followers';
    protected $fillable = ['profile_id', 'user_id', 'is_page'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
}