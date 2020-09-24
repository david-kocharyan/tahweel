<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CastomerWarrantySave extends Model
{
    CONST TYPE = array(0=>"Wait", 1=>"Sent", 2=>"No email");

    public function inspector()
    {
        return $this->belongsTo(User::class, "inspector_id", "id");
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

}
