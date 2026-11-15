<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BimbelClass extends Model
{
    use HasFactory;

    protected $table = 'bimbel_classes';

    protected $fillable = [
        'teacher_id',
        'mapel_id',
        'nama_bimbel',
        'deskripsi',
        'harga',
        'hari_jadwal',
        'jam_mulai',
        'jam_selesai',
        'kuota_maksimal',
        'status',
        'created_by_role',
        'created_by_user_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function creatorUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(BimbelEnrollment::class, 'bimbel_class_id');
    }

    public function sessions()
    {
        return $this->hasMany(BimbelSession::class, 'bimbel_class_id');
    }
}
