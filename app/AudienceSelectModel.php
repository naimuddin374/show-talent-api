<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudienceSelectModel extends Model
{
    protected $table = 'audience_selects';
    protected $fillable = ['ad_id', 'user_ids', 'age_start', 'age_end', 'country_ids', 'city_ids'];
}
