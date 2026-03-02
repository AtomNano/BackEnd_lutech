<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // WAJIB DIMASUKKAN KE FILLABLE, jika tidak, field ini akan diabaikan Laravel saat User::create()
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // HELPER METHODS: 
    // Jangan pernah mengecek string "$user->role === 'admin'" bertebaran di Controller atau Blade/Frontend.
    // Bungkus dalam fungsi ini agar jika nama role berubah di masa depan, 
    // kamu hanya perlu memperbaiki logika di satu tempat ini saja. (Prinsip DRY - Don't Repeat Yourself)

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}

