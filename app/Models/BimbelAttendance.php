<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbelAttendance extends Model
{
    use HasFactory;

    protected $table = 'bimbel_attendances';

    protected $fillable = [
        'bimbel_session_id',
        'student_id',
        'status',
        'catatan',
        'wa_sent',
    ];

    public function session()
    {
        return $this->belongsTo(BimbelSession::class, 'bimbel_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
