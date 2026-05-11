<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
        'avatar', 'is_active', 'preferred_language',
        'last_lat', 'last_lng', 'last_seen_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function agency()
    {
        return $this->hasOne(Agency::class);
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class);
    }

    public function sosRequests()
    {
        return $this->hasMany(SOSRequest::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'gov_admin']);
    }

    public function isAgencyAdmin(): bool
    {
        return $this->role === 'agency_admin';
    }
}
