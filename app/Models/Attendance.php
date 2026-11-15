<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'student_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'scanned_by',
        'wa_sent_status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
