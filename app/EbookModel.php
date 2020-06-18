<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\PageModel;
use App\ChapterModel;


class EbookModel extends Model
{
    protected $table = 'ebooks';
    protected $fillable = ['user_id', 'name', 'author_name', 'publication_date', 'preface', 'summery', 'author_summery', 'preface', 'front_image', 'back_image', 'price', 'status', 'reject_note', 'reopen_note'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function page(){
        return $this->belongsTo(PageModel::class);
    }
    public function chapter(){
        return $this->hasMany(ChapterModel::class, 'ebook_id');
    }
    // public function likes(){
    //     return $this->hasMany(PostLikeModel::class, 'post_id');
    // }
    // public function comments(){
    //     return $this->hasMany(PostCommentModel::class, 'post_id');
    // }
}
