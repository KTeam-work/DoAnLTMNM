<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_code',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Quan hệ - Chủ trọ
    |--------------------------------------------------------------------------
    */

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }


    public function ownedContracts()
    {
        return $this->hasMany(Contract::class, 'owner_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Quan hệ - Người thuê
    |--------------------------------------------------------------------------
    */

    public function viewingAppointments()
    {
        return $this->hasMany(
            ViewingAppointment::class,
            'tenant_id'
        );
    }


    public function rentedContracts()
    {
        return $this->hasMany(
            Contract::class,
            'tenant_id'
        );
    }
}