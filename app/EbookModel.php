<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EbookModel extends Model
{
    protected $table = 'ebooks';
    protected $fillable = ['user_id', 'category_id', 'title', 'language', 'author_id', 'publication_date', 'ebook_summery', 'author_summery', 'number_of_chapter', 'preface', 'font_image', 'back_image', 'price', 'status', 'reject_note', 'reopen_note'];
}
