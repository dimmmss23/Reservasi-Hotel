@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Invoice Card -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0"><i class="bi bi-receipt"></i> Invoice & Detail Reservasi</h4>
                            <small>No. Invoice: #{{ str_pad($reservasi->id, 6, '0', STR_PAD_LEFT) }}</small>
                        </div>
                        <div class="col-auto">
                            <button onclick="window.print()" class="btn btn-light btn-sm">
                                <i class="bi bi-printer"></i> Cetak Invoice
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> <strong>{{ session('success') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> <strong>{{ session('error') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Hotel Info & Tanggal -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Hotel D'Mas</h5>
                            <p class="mb-0 text-muted">
                                Jl. Contoh No. 123<br>
                                Jakarta, Indonesia<br>
                                Telp: (021) 1234-5678<br>
                                Email: info@hoteldmas.com
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Tanggal Booking</h6>
                            <p class="mb-2">{{ $reservasi->created_at->format('d F Y, H:i') }}</p>
                            
                            <h6 class="text-muted mt-3">Status Reservasi</h6>
                            @if($reservasi->status == 'pending' || $reservasi->status == 'menunggu')
                                <span class="badge bg-warning text-dark fs-6">Menunggu Konfirmasi</span>
                            @elseif($reservasi->status == 'confirmed' || $reservasi->status == 'disetujui')
                                <span class="badge bg-success fs-6">Dikonfirmasi</span>
                            @elseif($reservasi->status == 'selesai')
                                <span class="badge bg-info fs-6">Selesai</span>
                            @elseif($reservasi->status == 'dibatalkan')
                                <span class="badge bg-secondary fs-6">Dibatalkan</span>
                            @elseif($reservasi->status == 'ditolak')
                                <span class="badge bg-danger fs-6">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Informasi Tamu -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="bi bi-person-circle"></i> Informasi Tamu</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="35%"><strong>Nama Tamu</strong></td>
                                        <td>: {{ $reservasi->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>: {{ $reservasi->user->email }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Reservasi Kamar -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="bi bi-door-open"></i> Detail Reservasi Kamar</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kamar</th>
                                        <th>Tipe</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Durasi</th>
                                        <th>Harga/Malam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $checkIn = \Carbon\Carbon::parse($reservasi->check_in);
                                        $checkOut = \Carbon\Carbon::parse($reservasi->check_out);
                                        $durasi = $checkIn->diffInDays($checkOut);
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $reservasi->kamar->nama_kamar }}</strong></td>
                                        <td>{{ $reservasi->kamar->tipe_kamar }}</td>
                                        <td>{{ $checkIn->format('d M Y') }}</td>
                                        <td>{{ $checkOut->format('d M Y') }}</td>
                                        <td>{{ $durasi }} malam</td>
                                        <td>Rp {{ number_format($reservasi->kamar->harga, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ringkasan Pembayaran -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3"><i class="bi bi-calculator"></i> Ringkasan Biaya</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td>Harga per malam</td>
                                            <td class="text-end">Rp {{ number_format($reservasi->kamar->harga, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah malam</td>
                                            <td class="text-end">× {{ $durasi }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr></td>
                                        </tr>
                                        <tr class="fw-bold fs-5">
                                            <td>Total Pembayaran</td>
                                            <td class="text-end text-success">
                                                Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Informasi Pembayaran -->
                    @if($reservasi->payment)
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="bi bi-credit-card"></i> Informasi Pembayaran</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th width="40%">Metode Pembayaran</th>
                                            <td>{{ $reservasi->payment->metode_pembayaran }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status Pembayaran</th>
                                            <td>{!! $reservasi->payment->statusBadge !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Upload</th>
                                            <td>{{ $reservasi->payment->tanggal_upload ? $reservasi->payment->tanggal_upload->format('d M Y H:i') : '-' }}</td>
                                        </tr>
                                        @if($reservasi->payment->status == 'diterima' || $reservasi->payment->status == 'ditolak')
                                            <tr>
                                                <th>Tanggal Validasi</th>
                                                <td>{{ $reservasi->payment->tanggal_validasi ? $reservasi->payment->tanggal_validasi->format('d M Y H:i') : '-' }}</td>
                                            </tr>
                                            @if($reservasi->payment->validator)
                                                <tr>
                                                    <th>Divalidasi Oleh</th>
                                                    <td>{{ $reservasi->payment->validator->name }}</td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if($reservasi->payment->catatan_admin)
                                            <tr>
                                                <th>Catatan Admin</th>
                                                <td>{{ $reservasi->payment->catatan_admin }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    @if($reservasi->payment->bukti_pembayaran)
                                        <h6 class="mb-2">Bukti Transfer</h6>
                                        <div class="text-center border rounded p-2 bg-light">
                                            <img src="{{ asset('storage/' . $reservasi->payment->bukti_pembayaran) }}" 
                                                 alt="Bukti Pembayaran" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 250px; cursor: pointer;"
                                                 onclick="window.open(this.src, '_blank')">
                                            <p class="text-muted mt-2 mb-0"><small>Klik untuk memperbesar</small></p>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle"></i> Bukti pembayaran belum diupload
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action untuk pembayaran ditolak -->
                        @if($reservasi->payment->status == 'ditolak')
                            <div class="alert alert-danger">
                                <h6 class="alert-heading"><i class="bi bi-x-circle"></i> Pembayaran Ditolak</h6>
                                <p class="mb-2">{{ $reservasi->payment->catatan_admin ?? 'Pembayaran Anda ditolak oleh admin.' }}</p>
                                <hr>
                                <p class="mb-0"><small><strong>Catatan:</strong> Reservasi yang sudah ditolak tidak dapat diproses kembali. Silakan buat reservasi baru jika ingin melakukan pemesanan kamar.</small></p>
                            </div>
                        @elseif($reservasi->payment->status == 'diterima')
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <strong>Pembayaran Anda telah diverifikasi dan diterima.</strong> Terima kasih telah melakukan pembayaran.
                            </div>
                        @elseif($reservasi->payment->status == 'menunggu_validasi')
                            <div class="alert alert-info">
                                <i class="bi bi-hourglass-split"></i> <strong>Pembayaran Anda sedang dalam proses validasi.</strong> Mohon tunggu konfirmasi dari admin.
                            </div>
                        @endif

                    @else
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Pembayaran Belum Dilakukan</h6>
                            <p class="mb-0">Anda belum melakukan pembayaran untuk reservasi ini. Silakan lakukan pembayaran untuk menyelesaikan reservasi.</p>
                        </div>
                        @if($reservasi->status != 'ditolak')
                            <div class="text-center mb-3">
                                <a href="{{ route('tamu.payment.upload', $reservasi->id) }}" class="btn btn-success btn-lg">
                                    <i class="bi bi-credit-card"></i> Bayar Sekarang
                                </a>
                            </div>
                        @endif
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="text-center mt-4">
                        <a href="{{ route('tamu.reservasi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Reservasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-info-circle"></i> Informasi Penting</h6>
                    <ul class="mb-0 text-muted" style="font-size: 0.9rem;">
                        <li>Check-in dimulai pukul 14:00 WIB</li>
                        <li>Check-out paling lambat pukul 12:00 WIB</li>
                        <li>Harap membawa identitas (KTP/SIM/Paspor) saat check-in</li>
                        <li>Untuk perubahan atau pembatalan, silakan hubungi customer service</li>
                        <li>Simpan invoice ini sebagai bukti reservasi Anda</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .navbar, footer {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
