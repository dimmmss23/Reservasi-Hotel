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

    // Cek apakah kamar sudah direservasi (status pending atau disetujui)
    public function isReserved()
    {
        return $this->reservasis()
            ->whereIn('status', ['pending', 'disetujui'])
            ->exists();
    }

    // Ambil reservasi aktif
    public function activeReservation()
    {
        return $this->reservasis()
            ->whereIn('status', ['pending', 'disetujui'])
            ->with('user')
            ->first();
    }

    // Cek apakah kamar tersedia di tanggal tertentu
    public function isAvailableOnDates($checkIn, $checkOut)
    {
        // Cek apakah ada reservasi yang bentrok dengan tanggal yang diminta
        // Reservasi bentrok jika:
        // 1. Check-in baru di antara check-in dan check-out reservasi lama
        // 2. Check-out baru di antara check-in dan check-out reservasi lama
        // 3. Check-in baru sebelum check-in lama dan check-out baru setelah check-out lama
        
        $conflict = $this->reservasis()
            ->whereIn('status', ['pending', 'disetujui'])
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
