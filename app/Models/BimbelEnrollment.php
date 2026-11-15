<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbelEnrollment extends Model
{
    use HasFactory;

    protected $table = 'bimbel_enrollments';

    protected $fillable = [
        'bimbel_class_id',
        'student_id',
        'status_pembayaran',
        'tanggal_daftar',
    ];

    public function bimbelClass()
    {
        return $this->belongsTo(BimbelClass::class, 'bimbel_class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
