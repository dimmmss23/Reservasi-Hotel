<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Tamu: Halaman upload bukti pembayaran
    public function uploadForm($reservasiId)
    {
        $reservasi = Reservasi::with(['kamar', 'payment'])->findOrFail($reservasiId);
        
        // Pastikan reservasi milik user yang login
        if ($reservasi->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Jika reservasi sudah ditolak, tidak bisa upload lagi
        if ($reservasi->status == 'ditolak' || ($reservasi->payment && $reservasi->payment->status == 'ditolak')) {
            return redirect()->route('tamu.payment.show', $reservasiId)
                ->with('error', 'Reservasi yang sudah ditolak tidak dapat diproses kembali. Silakan buat reservasi baru.');
        }

        return view('tamu.payment.upload', compact('reservasi'));
    }

    // Tamu: Proses upload bukti pembayaran
    public function upload(Request $request, $reservasiId)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        $reservasi = Reservasi::with('kamar')->findOrFail($reservasiId);

        // Pastikan reservasi milik user yang login
        if ($reservasi->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Jika reservasi sudah ditolak, tidak bisa upload lagi
        if ($reservasi->status === 'ditolak' && $reservasi->payment && $reservasi->payment->status === 'ditolak') {
            return redirect()->route('tamu.payment.show', $reservasiId)
                ->with('error', 'Reservasi yang sudah ditolak tidak dapat diproses kembali. Silakan buat reservasi baru.');
        }

        // Hitung total pembayaran
        $checkIn = new \DateTime($reservasi->check_in);
        $checkOut = new \DateTime($reservasi->check_out);
        $jumlahHari = $checkOut->diff($checkIn)->days;
        $totalBayar = $jumlahHari * $reservasi->kamar->harga;

        // Upload file
        $file = $request->file('bukti_pembayaran');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('payments', $filename, 'public');

        // Create or update payment
        $payment = Payment::updateOrCreate(
            ['reservasi_id' => $reservasiId],
            [
                'jumlah_bayar' => $totalBayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $path,
                'status' => 'menunggu_validasi',
                'tanggal_upload' => now(),
            ]
        );

        return redirect()->route('tamu.payment.show', $reservasiId)
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu validasi admin.');
    }

    // Tamu: Lihat detail pembayaran
    public function show($reservasiId)
    {
        $reservasi = Reservasi::with(['kamar', 'payment.validator'])->findOrFail($reservasiId);
        
        // Pastikan reservasi milik user yang login
        if ($reservasi->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('tamu.payment.show', compact('reservasi'));
    }

    // Admin: List semua pembayaran untuk validasi
    public function adminIndex(Request $request)
    {
        $query = Payment::with(['reservasi.user', 'reservasi.kamar'])
            ->orderBy('tanggal_upload', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10);

        return view('admin.payment.index', compact('payments'));
    }

    // Admin: Halaman validasi pembayaran
    public function adminValidate($paymentId)
    {
        $payment = Payment::with(['reservasi.user', 'reservasi.kamar'])->findOrFail($paymentId);

        return view('admin.payment.validate', compact('payment'));
    }

    // Admin: Proses validasi pembayaran
    public function adminProcessValidation(Request $request, $paymentId)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $payment = Payment::findOrFail($paymentId);
        
        $payment->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'tanggal_validasi' => now(),
            'validated_by' => Auth::id(),
        ]);

        // Update status reservasi jika pembayaran diterima
        if ($request->status === 'diterima') {
            $payment->reservasi->update(['status' => 'confirmed']);
        } elseif ($request->status === 'ditolak') {
            $payment->reservasi->update(['status' => 'pending']);
        }

        return redirect()->route('admin.payment.index')
            ->with('success', 'Pembayaran berhasil divalidasi.');
    }
}
