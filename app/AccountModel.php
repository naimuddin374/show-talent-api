<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    protected $table = 'accounts';
    protected $fillable = ['user_id', 'type', 'last_balance', 'amount', 'available_balance', 'comment', 'status'];
}
