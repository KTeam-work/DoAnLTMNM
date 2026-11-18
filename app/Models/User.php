<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Property;

use App\Services\ResendService;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use  HasFactory, Notifiable;

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

    public function services()
    {
        return $this->hasMany(Service::class, 'owner_id');
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

    
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function postReports()
    {
        return $this->hasMany(PostReport::class, 'reporter_id');
    }


    // sendPasswordResetNotification
      public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ]);

        $html = '
            <div style="font-family:Arial,sans-serif;
                        max-width:600px;margin:auto;padding:25px;
                        border:1px solid #ddd;border-radius:10px;">

                <h2>Yêu cầu đặt lại mật khẩu</h2>

                <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu
                   cho tài khoản của bạn.</p>

                <p>Nhấn vào nút bên dưới để tạo mật khẩu mới:</p>

                <a href="' . e($url) . '"
                   style="display:inline-block;
                          padding:12px 22px;
                          background:#2563eb;
                          color:white;
                          text-decoration:none;
                          border-radius:6px;">
                    Đặt lại mật khẩu
                </a>

                <p>Liên kết có hiệu lực trong 60 phút.</p>

                <p>Nếu bạn không yêu cầu thao tác này,
                   hãy bỏ qua email.</p>
            </div>
        ';

        app(ResendService::class)->sendEmail(
            $this->email,
            'Yêu cầu đặt lại mật khẩu',
            $html
        );
    }
}
