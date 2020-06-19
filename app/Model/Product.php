<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function languages()
    {
        return $this->belongsToMany(Language::class, "product_languages", "product_id", "language_id")->withPivot("name", "description")->withTimestamps();
    }
}
