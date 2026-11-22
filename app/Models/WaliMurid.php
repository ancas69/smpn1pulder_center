<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WaliMurid extends Model
{
    use HasFactory;

    protected $table = 'wali_murids';

    protected $fillable = [
        'user_id',
        'nama_wali',
        'no_whatsapp',
        'hubungan',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'wali_id');
    }

    
}
