<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\CategoryModel;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $fillable = ['user_id', 'name', 'email', 'contact', 'image', 'category_id', 'creation_date', 'expiration_date', 'view', 'follow', 'status', 'bio', 'admin_id', 'reject_note', 'points'];


    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function category(){
        return $this->belongsTo(CategoryModel::class);
    }
}