<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function images()
    {
        return $this->hasMany("App\Model\InspectionImages", "inspection_id", "id");
    }

    public function issue()
    {
        return $this->belongsTo("App\Model\Issue", "issue_id", "id");
    }

    public function phases()
    {
        return $this->hasMany("App\Model\Phase", "inspection_id", "id");
    }

    public function currentPhase()
    {
        return $this->hasOne("App\Model\Phase", "inspection_id", "id")->latest();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }

}
