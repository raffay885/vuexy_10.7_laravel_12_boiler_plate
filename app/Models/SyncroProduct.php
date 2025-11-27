<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EsetProduct;

class SyncroProduct extends Model
{
    protected $guarded = [];
    protected $table = 'syncro_products';

    public function esetProduct()
    {
        return $this->hasOne(EsetProduct::class, 'syncro_product_id', 'id');
    }
}
