<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{

    public function plumber()
    {
        return $this->belongsTo("App\User", 'plumber_id', 'id');
    }

    public function inspector()
    {
        return $this->hasOneThrough(  'App\User', 'App\Model\InspectionInspector',  'inspection_id', 'id', 'id', 'inspector_id');
    }


}
