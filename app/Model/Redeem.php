<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    const STATUS = ['New' => 0, 'Delivered' => 1];

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "plumber_id", "id");
    }
}
