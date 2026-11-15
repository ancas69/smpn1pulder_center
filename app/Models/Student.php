<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'wali_id',
        'nisn',
        'kelas',
        'qr_code_token',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wali()
    {
        return $this->belongsTo(WaliMurid::class, 'wali_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function activities()
    {
        return $this->hasMany(StudentActivity::class);
    }

    public function bimbelEnrollments()
    {
        return $this->hasMany(BimbelEnrollment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
