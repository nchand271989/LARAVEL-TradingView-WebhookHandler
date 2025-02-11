<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $keyType = 'string';                                                                              // Ensure id is treated as a string
    public $incrementing = false;                                                                               // Disable auto-increment


    /** The attributes that are mass assignable. @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'terms_and_conditions_id',  
        'privacy_policy_id',
    ];

    /** The attributes that should be hidden for serialization. @var array<int, string> */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /** The accessors to append to the model's array form. @var array<int, string> */
    protected $appends = [
        'profile_photo_url',
    ];

    /** Get the attributes that should be cast. @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

     /** Boot method to set default values when creating a new user. */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = generate_snowflake_id();
            }
            $user->is_admin = false;                                                                            // Ensure new users are always non-admin
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }

    /** Override the getKey() function to return UUID as a string. */
    public function getKey()
    {
        return (string) $this->id;
    }

    /** Fix Laravel's routeNotificationFor() to ensure UUIDs work correctly. */
    public function routeNotificationFor($driver)
    {
        if ($driver === 'mail') {
            return $this->email;
        }

        return (string) $this->id;
    }
}
