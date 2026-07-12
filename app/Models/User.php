<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'province', 'city',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function catinProfile()
    {
        return $this->hasOne(CatinProfile::class);
    }

    public function donorEntry()
    {
        return $this->hasOne(DonorDirectory::class);
    }

    public function isCatin(): bool { return $this->role === 'catin'; }
    public function isBidan(): bool { return $this->role === 'bidan'; }
    public function isAdminFaskes(): bool { return $this->role === 'admin_faskes'; }
    public function isAdmin(): bool { return $this->role === 'admin_sistem'; }
    public function isRelawan(): bool { return $this->role === 'relawan'; }
}
