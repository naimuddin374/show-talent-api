<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\PageModel;
use App\ClassifiedGalleryModel;
use App\CityModel;

class ClassifiedModel extends Model
{
    protected $table = 'classifieds';
    protected $fillable = ['user_id', 'page_id', 'type', 'category_id', 'contact', 'title', 'description', 'image', 'price', 'status', 'admin_id', 'reject_note', 'is_unread', 'points', 'created_at'];

    public function user(){
        return $this->belongsTo(UserModel::class, 'user_id');
    }
    public function page(){
        return $this->belongsTo(PageModel::class, 'page_id');
    }
    public function gallery(){
        return $this->belongsTo(ClassifiedGalleryModel::class);
    }
}