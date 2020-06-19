<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, "product_languages", "language_id", "product_id")->withPivot("name", "description")->withTimestamps();
    }
}
