<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PostLikeModel;
use App\UserModel;
use App\CategoryModel;
use App\PageModel;


class PostModel extends Model
{
    protected $table = 'posts';
    protected $fillable = ['user_id', 'type', 'category_id', 'page_id', 'title', 'description', 'newslink', 'video', 'image', 'status'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function category(){
        return $this->belongsTo(CategoryModel::class);
    }
    public function page(){
        return $this->belongsTo(PageModel::class);
    }
    public function likes(){
        return $this->hasMany(PostLikeModel::class, 'post_id');
    }
}