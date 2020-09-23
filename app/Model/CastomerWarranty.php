<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CastomerWarranty extends Model
{
    public function inspector()
    {
        return $this->hasOneThrough("", "", "", "");
    }

    public function inspection()
    {
        return $this->hasOne(InspectionForm::class, "inspecton_id", "id");
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, "customer_id", "id");
    }
}
