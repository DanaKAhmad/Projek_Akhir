<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
         'name',
         'email',
        'password',
        'role',
        'no_telp',
        'jenis_kelamin',
        'tanggal_masuk',
        'tanggal_lahir',
        'alamat'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /*
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }
        */
        public function karyawan()
{
    return $this->hasOne(Karyawan::class, 'user_id'); // pastikan user_id benar
     
}
}
