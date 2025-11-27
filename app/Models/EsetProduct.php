<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SyncroProduct;

class EsetProduct extends Model
{
    protected $guarded = [];
    protected $table = 'eset_products';

    public function syncroProduct()
    {
        return $this->belongsTo(SyncroProduct::class, 'syncro_product_id', 'id');
    }
}
