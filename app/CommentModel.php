<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;


class CommentModel extends Model
{
    protected $table = 'comments';
    protected $fillable = ['user_id', 'ebook_id', 'rating', 'comment', 'status'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
}