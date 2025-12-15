<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Reservasi;

class ReservasiController extends Controller
{
    public function store(Request $r)
    {
        // Validasi input
        $validated = $r->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Cek apakah kamar tersedia di tanggal yang diminta
        $kamar = Kamar::find($validated['kamar_id']);
        
        if (!$kamar->isAvailableOnDates($validated['check_in'], $validated['check_out'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Maaf, kamar ini sudah direservasi pada tanggal yang Anda pilih. Silakan pilih tanggal lain atau kamar lain.');
        }

        // Hitung total harga
        $checkIn = \Carbon\Carbon::parse($validated['check_in']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out']);
        $durasi = $checkIn->diffInDays($checkOut);
        $total = $durasi * $kamar->harga;
        
        // Simpan reservasi
        $reservasi = Reservasi::create([
            'user_id' => auth()->id(),
            'kamar_id' => $validated['kamar_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_harga' => $total,
        ]);
        
        // Redirect langsung ke halaman pembayaran
        return redirect()->route('tamu.payment.upload', $reservasi->id)
            ->with('success', 'Reservasi berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function listUser()
    {
        $data = Reservasi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('tamu.reservasi.index', compact('data'));
    }

    public function cancel($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$reservasi) {
            return back()->with('error', 'Reservasi tidak ditemukan.');
        }

        // Hanya bisa cancel jika status masih pending atau menunggu
        if (!in_array($reservasi->status, ['pending', 'menunggu'])) {
            return back()->with('error', 'Tidak dapat membatalkan reservasi dengan status ' . $reservasi->status);
        }

        $reservasi->update(['status' => 'dibatalkan']);
        
        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    // ADMIN
    public function indexAdmin()
    {
        $data = Reservasi::with(['user', 'kamar', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.reservasi.index', compact('data'));
    }

    public function show($id)
    {
        $reservasi = Reservasi::with(['user', 'kamar', 'payment.validator'])->findOrFail($id);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function approve($id)
    {
        $reservasi = Reservasi::with('payment')->find($id);
        
        if (!$reservasi) {
            return back()->with('error', 'Reservasi tidak ditemukan.');
        }

        // Update status reservasi
        $reservasi->update(['status' => 'disetujui']);

        // Jika ada pembayaran, update status pembayaran juga
        if ($reservasi->payment) {
            $reservasi->payment->update([
                'status' => 'diterima',
                'tanggal_validasi' => now(),
                'validated_by' => auth()->id(),
                'catatan_admin' => 'Pembayaran dan reservasi disetujui.'
            ]);
        }

        return back()->with('success', 'Pembayaran dan reservasi berhasil disetujui.');
    }

    public function reject($id)
    {
        $reservasi = Reservasi::with('payment')->find($id);
        
        if (!$reservasi) {
            return back()->with('error', 'Reservasi tidak ditemukan.');
        }

        // Update status reservasi
        $reservasi->update(['status' => 'ditolak']);

        // Jika ada pembayaran, update status pembayaran juga
        if ($reservasi->payment) {
            $reservasi->payment->update([
                'status' => 'ditolak',
                'tanggal_validasi' => now(),
                'validated_by' => auth()->id(),
                'catatan_admin' => 'Pembayaran tidak valid atau reservasi ditolak. Silakan upload ulang bukti pembayaran yang benar.'
            ]);
        }

        return back()->with('success', 'Pembayaran dan reservasi berhasil ditolak.');
    }

    public function checkout($id)
    {
        $reservasi = Reservasi::find($id);
        
        if (!$reservasi) {
            return back()->with('error', 'Reservasi tidak ditemukan.');
        }

        if ($reservasi->status !== 'disetujui') {
            return back()->with('error', 'Hanya reservasi yang disetujui yang bisa di-checkout.');
        }

        $reservasi->update(['status' => 'selesai']);
        
        return back()->with('success', 'Checkout berhasil. Kamar ' . $reservasi->kamar->nama_kamar . ' sekarang tersedia kembali.');
    }

    public function cancelByAdmin($id)
    {
        $reservasi = Reservasi::with('payment')->find($id);
        
        if (!$reservasi) {
            return back()->with('error', 'Reservasi tidak ditemukan.');
        }

        // Hanya bisa cancel jika status disetujui dan sudah dibayar
        if ($reservasi->status !== 'disetujui') {
            return back()->with('error', 'Hanya reservasi yang disetujui yang bisa dibatalkan oleh admin.');
        }

        if (!$reservasi->payment || $reservasi->payment->status !== 'diterima') {
            return back()->with('error', 'Reservasi belum memiliki pembayaran yang diterima.');
        }

        // Update status reservasi
        $reservasi->update(['status' => 'dibatalkan']);

        // Update status pembayaran menjadi dibatalkan
        $reservasi->payment->update([
            'status' => 'dibatalkan',
            'tanggal_validasi' => now(),
            'validated_by' => auth()->id(),
            'catatan_admin' => 'Reservasi dibatalkan oleh admin atas permintaan tamu. Pembayaran akan dikurangkan dari total pendapatan.'
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan. Pembayaran akan dikurangkan dari total pendapatan.');
    }
}
