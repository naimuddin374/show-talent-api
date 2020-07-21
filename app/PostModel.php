<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PostLikeModel;
use App\UserModel;
use App\CategoryModel;
use App\PageModel;
use App\PostCommentModel;


class PostModel extends Model
{
    protected $table = 'posts';
    protected $fillable = ['user_id', 'type', 'category_id', 'page_id', 'title', 'description', 'newslink', 'video', 'image', 'status', 'reject_note', 'admin_id', 'is_editor'];

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
    public function comments(){
        return $this->hasMany(PostCommentModel::class, 'post_id');
    }
    public function moderator(){
        return $this->belongsTo(UserModel::class, 'admin_id');
    }
}
