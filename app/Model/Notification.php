<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
//    Notificaion Types
    const INSPECTION_TYPE = 0;
    const ADMIN_TYPE = 1;
    const ADMIN_LINK_TYPE = 2;

    public function user()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }
}
