<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar', // Foto Profil
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

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    // isAdmin returns true for both 'admin' and 'super_admin'
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class);
    }

    public function defaultWorkspace(): ?Workspace
    {
        return $this->workspaces()->where('is_default', true)->first();
    }
}

