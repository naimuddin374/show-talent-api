<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\CategoryModel;

class PreferenceModel extends Model
{
    protected $table = 'preferences';
    protected $fillable = ['user_id', 'category_id'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function category(){
        return $this->belongsTo(CategoryModel::class);
    }
}
