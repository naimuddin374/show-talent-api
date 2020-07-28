<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserInfoModel;
use App\FollowerModel;
use App\EduInfoModel;
use App\WorkExpModel;

class UserModel extends Model
{
    protected $table = 'users';
    protected $fillable = ['full_name', 'name', 'contact', 'email', 'password', 'image', 'type', 'status', 'balance', 'points', 'reset_code'];

    public function userInfo(){
        return $this->hasMany(UserInfoModel::class, 'user_id');
    }
    public function education(){
        return $this->hasMany(EduInfoModel::class, 'user_id');
    }
    public function experience(){
        return $this->hasMany(WorkExpModel::class, 'user_id');
    }
    public function follower(){
        return $this->hasMany(FollowerModel::class, 'profile_id');
    }
    public function following(){
        return $this->hasMany(FollowerModel::class, 'user_id');
    }
}