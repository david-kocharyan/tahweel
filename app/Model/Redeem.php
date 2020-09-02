<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "plumber_id", "id");
    }

    public function ProdWithLang()
    {
        return $this->hasManyThrough(ProductLanguage::class, Product::class, "product_id", "id", "id", "product_id");
    }
}
