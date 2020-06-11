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
    protected $fillable = ['user_id', 'page_id', 'type', 'category_id', 'contact', 'email', 'title', 'description', 'image', 'price', 'city_id', 'address', 'status'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function category(){
        return $this->belongsTo(CategoryModel::class);
    }
    public function page(){
        return $this->belongsTo(PageModel::class);
    }
    public function gallery(){
        return $this->belongsTo(ClassifiedGalleryModel::class);
    }
}
