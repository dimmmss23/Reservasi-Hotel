<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Reservasi;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // Total Kamar
        $totalKamar = Kamar::count();
        
        // Total Reservasi
        $totalReservasi = Reservasi::count();
        
        // Reservasi Pending (menunggu validasi)
        $reservasiPending = Reservasi::whereIn('status', ['pending', 'menunggu'])->count();
        
        // Reservasi Disetujui
        $reservasiDisetujui = Reservasi::where('status', 'disetujui')->count();
        
        // Total Tamu
        $totalTamu = User::where('role', 'tamu')->count();
        
        // Total Pendapatan (hanya dari pembayaran yang diterima)
        // Pembayaran yang dibatalkan statusnya sudah berubah jadi 'dibatalkan', jadi tidak dihitung
        $totalPendapatan = Payment::where('status', 'diterima')
            ->sum('jumlah_bayar');
        
        // Reservasi terbaru
        $reservasiTerbaru = Reservasi::with(['kamar', 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Statistik kamar berdasarkan tipe
        $kamarPerTipe = Kamar::select('tipe', DB::raw('count(*) as total'))
            ->groupBy('tipe')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalKamar',
            'totalReservasi',
            'reservasiPending',
            'reservasiDisetujui',
            'totalTamu',
            'totalPendapatan',
            'reservasiTerbaru',
            'kamarPerTipe'
        ));
    }

    public function tamu()
    {
        $userId = auth()->id();
        
        // Total kamar tersedia
        $totalKamar = Kamar::count();
        
        // Statistik reservasi user
        $totalReservasi = Reservasi::where('user_id', $userId)->count();
        $reservasiPending = Reservasi::where('user_id', $userId)
            ->whereIn('status', ['pending', 'menunggu'])
            ->count();
        $reservasiDisetujui = Reservasi::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->count();
        
        // Kamar terbaru (3 kamar)
        $kamarTerbaru = Kamar::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Reservasi terbaru user (5 terakhir)
        $reservasiTerbaru = Reservasi::with(['kamar', 'payment'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('tamu.home', compact(
            'totalKamar',
            'totalReservasi',
            'reservasiPending',
            'reservasiDisetujui',
            'kamarTerbaru',
            'reservasiTerbaru'
        ));
    }
}
