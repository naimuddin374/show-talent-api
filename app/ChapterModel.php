<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChapterModel extends Model
{
    protected $table = "chapters";
    protected $fillable = ["ebook_id", "sequence", "book", "image", "status", "reject_note", "reopen_note"];
}