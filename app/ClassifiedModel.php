<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\CategoryModel;
use App\PageModel;
use App\ClassifiedGalleryModel;

class ClassifiedModel extends Model
{
    protected $table = 'classifieds';
    protected $fillable = ['user_id', 'page_id', 'type', 'category_id', 'contact', 'email', 'title', 'description', 'image', 'price', 'currency', 'address', 'status', 'admin_id', 'reject_note', 'is_unread'];

    public function user(){
        return $this->belongsTo(UserModel::class, 'user_id');
    }
    public function category(){
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }
    public function page(){
        return $this->belongsTo(PageModel::class, 'page_id');
    }
    public function gallery(){
        return $this->belongsTo(ClassifiedGalleryModel::class);
    }
}