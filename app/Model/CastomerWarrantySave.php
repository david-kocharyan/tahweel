<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CastomerWarrantySave extends Model
{
    public function inspector()
    {
        return $this->belongsTo(User::class, "inspector_id", "id");
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    public function warranty()
    {
        return $this->hasMany(Certificate::class)->where('certificates.type', '=', 'castomer_warranty_saves.warranty_type');
    }
}
