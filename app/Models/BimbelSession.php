<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbelSession extends Model
{
    use HasFactory;

    protected $table = 'bimbel_sessions';

    protected $fillable = [
        'bimbel_class_id',
        'pertemuan_ke',
        'tanggal',
        'materi_pembahasan',
    ];

    public function bimbelClass()
    {
        return $this->belongsTo(BimbelClass::class, 'bimbel_class_id');
    }

    public function attendances()
    {
        return $this->hasMany(BimbelAttendance::class, 'bimbel_session_id');
    }

    public function progressReports()
    {
        return $this->hasMany(BimbelProgressReport::class, 'bimbel_session_id');
    }
}
