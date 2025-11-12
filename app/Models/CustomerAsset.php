<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAsset extends Model
{
    protected $guarded = [];
    protected $table = 'customer_assets';

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }
}
