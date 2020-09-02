<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
//    Notificaion Types
    const INSPECTION_TYPE = 0;

    public function user()
    {
        return $this->hasOne("App\User", "user_id", "id");
    }
}
