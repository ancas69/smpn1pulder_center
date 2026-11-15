<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbelProgressReport extends Model
{
    use HasFactory;

    protected $table = 'bimbel_progress_reports';

    protected $fillable = [
        'bimbel_class_id',
        'bimbel_session_id',
        'student_id',
        'teacher_id',
        'nilai_evaluasi',
        'catatan_perkembangan',
        'catatan_rekomendasi',
        'wa_sent',
    ];

    public function bimbelClass()
    {
        return $this->belongsTo(BimbelClass::class, 'bimbel_class_id');
    }

    public function session()
    {
        return $this->belongsTo(BimbelSession::class, 'bimbel_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
