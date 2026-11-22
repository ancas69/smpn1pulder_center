<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'role', 'login_attempts', 'unlock_token', 'unlock_token_expires_at', 'blokir'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function student()
    {
        // Asumsi tabel students punya kolom 'user_id'
        return $this->hasOne(Student::class);
    }

    public function guru()
    {
        return $this->hasOne(Teacher::class);
    }

    public function wali()
    {
        return $this->hasOne(WaliMurid::class);
    }

    /**
     * Berita yang ditulis oleh user ini.
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
