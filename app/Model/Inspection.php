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

    public function phases()
    {
        return $this->hasMany("App\Model\Phase", "inspection_id", "id");
    }

    public function currentPhase()
    {
        return $this->hasOne("App\Model\Phase", "inspection_id", "id")->latest();
    }

    public function issues()
    {
        return $this->hasMany("App\Model\InspectionForm", "inspection_id", "id");
    }

    public function issue_phase1()
    {
        return $this->hasMany("App\Model\InspectionForm", "inspection_id", "id")->where("phase", 1);
    }

    public function issue_phase2()
    {
        return $this->hasMany("App\Model\InspectionForm", "inspection_id", "id")->where("phase", 2);
    }

}
