<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\PageModel;
use App\ChapterModel;
use App\CategoryModel;
use App\CommentModel;


class EbookModel extends Model
{
    protected $table = 'ebooks';
    protected $fillable = ['category_id', 'user_id', 'page_id', 'name', 'author_name', 'publication_date', 'summary', 'author_summary', 'preface', 'front_image', 'back_image', 'price', 'status', 'reject_note', 'reopen_note', 'admin_id', 'is_unread', 'points', 'created_at'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function page(){
        return $this->belongsTo(PageModel::class);
    }
    public function chapter(){
        return $this->hasMany(ChapterModel::class, 'ebook_id');
    }
    public function category(){
        return $this->belongsTo(CategoryModel::class);
    }
    // public function likes(){
    //     return $this->hasMany(PostLikeModel::class, 'post_id');
    // }
    public function comments(){
        return $this->hasMany(CommentModel::class, 'ebook_id');
    }
}