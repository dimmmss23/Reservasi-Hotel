<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservasi_id',
        'jumlah_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan_admin',
        'tanggal_upload',
        'tanggal_validasi',
        'validated_by'
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_validasi' => 'datetime',
    ];

    // Relationship ke Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    // Relationship ke User (validator/admin)
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Helper untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-secondary">Belum Upload</span>',
            'menunggu_validasi' => '<span class="badge bg-warning text-dark">Menunggu Validasi</span>',
            'diterima' => '<span class="badge bg-success">Diterima</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'dibatalkan' => '<span class="badge bg-info"><i class="bi bi-arrow-counterclockwise"></i> Refund</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
