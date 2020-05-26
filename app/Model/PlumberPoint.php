<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlumberPoint extends Model
{
    const COEFFICIENT = 5;

    public function inspection()
    {
        return $this->belongsTo(Inspection::class, "inspection_id", "id");
    }
}
