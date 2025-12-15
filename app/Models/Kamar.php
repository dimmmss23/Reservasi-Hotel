<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $fillable = [
        'nama_kamar',
        'tipe',
        'harga',
        'jumlah_bed',
        'deskripsi',
        'foto'
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    // Cek apakah kamar sudah direservasi (status pending, menunggu, atau disetujui)
    public function isReserved()
    {
        return $this->reservasis()
            ->whereIn('status', ['pending', 'menunggu', 'disetujui', 'confirmed'])
            ->exists();
    }

    // Ambil semua reservasi aktif
    public function activeReservations()
    {
        return $this->reservasis()
            ->whereIn('status', ['pending', 'menunggu', 'disetujui', 'confirmed'])
            ->with('user')
            ->orderBy('check_in', 'asc')
            ->get();
    }

    // Ambil reservasi aktif terdekat (untuk backward compatibility)
    public function activeReservation()
    {
        return $this->reservasis()
            ->whereIn('status', ['pending', 'menunggu', 'disetujui', 'confirmed'])
            ->with('user')
            ->orderBy('check_in', 'asc')
            ->first();
    }

    // Ambil reservasi yang sedang berlangsung saat ini
    public function currentReservation()
    {
        $today = now()->format('Y-m-d');
        return $this->reservasis()
            ->whereIn('status', ['disetujui', 'confirmed'])
            ->where('check_in', '<=', $today)
            ->where('check_out', '>', $today)
            ->with('user')
            ->first();
    }

    // Cek apakah kamar tersedia di tanggal tertentu
    public function isAvailableOnDates($checkIn, $checkOut)
    {
        // Cek apakah ada reservasi yang bentrok dengan tanggal yang diminta
        // Termasuk reservasi yang sedang menunggu validasi pembayaran
        // Status yang dianggap "mengambil" kamar: pending, menunggu, disetujui, confirmed
        // Status yang TIDAK mengambil kamar: ditolak, dibatalkan, selesai
        
        $conflict = $this->reservasis()
            ->whereIn('status', ['pending', 'menunggu', 'disetujui', 'confirmed'])
            ->where(function($query) use ($checkIn, $checkOut) {
                // Cek overlap tanggal
                $query->where(function($q) use ($checkIn, $checkOut) {
                    // Check-in baru di dalam periode reservasi lama
                    $q->where('check_in', '<=', $checkIn)
                      ->where('check_out', '>', $checkIn);
                })
                ->orWhere(function($q) use ($checkIn, $checkOut) {
                    // Check-out baru di dalam periode reservasi lama
                    $q->where('check_in', '<', $checkOut)
                      ->where('check_out', '>=', $checkOut);
                })
                ->orWhere(function($q) use ($checkIn, $checkOut) {
                    // Reservasi baru melingkupi reservasi lama
                    $q->where('check_in', '>=', $checkIn)
                      ->where('check_out', '<=', $checkOut);
                });
            })
            ->exists();

        return !$conflict;
    }
}
