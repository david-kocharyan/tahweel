<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CastomerWarrantySave extends Model
{
    public function inspector()
    {
        return $this->hasOne(User::class, "inspector_id", "id");
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, "customer_id", "id");
    }
}
