<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdModel extends Model
{
    protected $table = 'ads';
    protected $fillable = ['user_id', 'page_id', 'category_id', 'title', 'image', 'video', 'hyperlink', 'start_date', 'end_date', 'status', 'reject_note', 'reopen_note'];
}
