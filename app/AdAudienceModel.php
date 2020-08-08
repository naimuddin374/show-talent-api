<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdAudienceModel extends Model
{
    protected $table = 'ad_audiences';
    protected $fillable = ['gender', 'age_start', 'age_end', 'countries', 'cities', 'ad_id'];
}