<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'kode_invoice',
        'student_id',
        'bimbel_class_id',
        'total_bayar',
        'metode_pembayaran',
        'status_pembayaran',
        'snap_token',
        'checkout_url',
        'paid_at',
        'wa_notified',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function bimbelClass()
    {
        return $this->belongsTo(BimbelClass::class, 'bimbel_class_id');
    }
}
