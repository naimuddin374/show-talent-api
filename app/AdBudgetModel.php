<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdBudgetModel extends Model
{
    protected $table = 'ad_budgets';
    protected $fillable = ['ad_id', 'amount', 'currency'];
}
