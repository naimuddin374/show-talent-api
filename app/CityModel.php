<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CountryModel;

class CityModel extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name', 'country_id', 'status'];

    
    public function country(){
        return $this->belongsTo(CountryModel::class, 'country_id');
    }
}