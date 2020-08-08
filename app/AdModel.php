<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserModel;
use App\PageModel;
use App\AdAudienceModel;
use App\AdBudgetModel;


class AdModel extends Model
{
    protected $table = 'ads';
    protected $fillable = ['user_id', 'page_id', 'category_id', 'title', 'image', 'video', 'website', 'start_date', 'end_date', 'status', 'reject_note', 'reopen_note', 'admin_id', 'is_unread', 'points'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
    public function page(){
        return $this->belongsTo(PageModel::class);
    }
    public function audience(){
        return $this->hasMany(AdAudienceModel::class, 'ad_id');
    }
    public function budget(){
        return $this->hasMany(AdBudgetModel::class, 'ad_id');
    }
}