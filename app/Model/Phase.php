<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    const REJECTED  = 0;

    const NEW       = 1;

    const COMPLETED = 2;

    const REPEATED  = 3;

    protected $guarded = [];
}
