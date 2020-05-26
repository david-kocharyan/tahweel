<?php

namespace App;

use App\Model\Inspection;
use App\Model\PlumberPoint;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    const ROLES = [
        "plumber" => 1,
        "inspector" => 2
    ];

    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens()
    {
        return $this->hasMany("App\Model\FcmToken", "user_id", "id");
    }

    public function points()
    {
        return $this->hasManyThrough(PlumberPoint::class, Inspection::class, "id", "id", "inspection_id");
    }
}
