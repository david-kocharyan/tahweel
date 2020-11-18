<?php

namespace App;

use App\Model\City;
use App\Model\Inspection;
use App\Model\Phone;
use App\Model\PlumberPoint;
use App\Model\Redeem;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    const ROLES = [
        "plumber" => 1,
        "inspector" => 2
    ];

    const ENGLISH = 1;
    const ARABIC = 2;

    use HasApiTokens, Notifiable;

    public function findForPassport($username)
    {
        $customUsername = 'username';
        return $this->where($customUsername, $username)->first();
    }

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'id'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens()
    {
        return $this->hasMany("App\Model\FcmToken", "user_id", "id");
    }

    public function tokensForAll()
    {
        return $this->hasOne("App\Model\FcmToken", "user_id", "id");
    }

    public function pointsEarned()
    {
        return $this->hasManyThrough(PlumberPoint::class, Inspection::class, "plumber_id", "inspection_id", "id", "id");
    }

    public function pointsRedeemed()
    {
        return $this->hasMany(Redeem::class, "plumber_id", "id");
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, "plumber_id", "id");
    }

    public function phone()
    {
        return $this->hasOne(Phone::class, "user_id", "id");
    }

    public function city()
    {
        return $this->belongsTo(City::class, "city_id", "id");
    }

}
