<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject; // Wajib untuk JWT
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass Assignment: Daftar kolom yang BOLEH diisi secara massal.
     * Ini yang menyebabkan error tadi karena 'name' dkk belum ada di sini.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang harus disembunyikan saat output JSON (Keamanan)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // =========================================================================
    // MEWAKILI INTERFACE JWT (Wajib ada agar tidak error saat Login)
    // =========================================================================

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
