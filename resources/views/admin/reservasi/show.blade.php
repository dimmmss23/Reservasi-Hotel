@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <!-- Invoice Card -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-success text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0"><i class="bi bi-receipt-cutoff"></i> Detail Reservasi & Validasi Pembayaran</h4>
                            <small>No. Invoice: #{{ str_pad($reservasi->id, 6, '0', STR_PAD_LEFT) }}</small>
                        </div>
                        <div class="col-auto">
                            <a href="/admin/reservasi" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
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

                    <div class="row">
                        <!-- Kolom Kiri: Invoice & Detail -->
                        <div class="col-md-7">
                            <!-- Info Tamu & Tanggal -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="bi bi-person-circle"></i> Informasi Tamu</h5>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td width="35%"><strong>Nama Tamu</strong></td>
                                            <td>{{ $reservasi->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>{{ $reservasi->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Booking</strong></td>
                                            <td>{{ $reservasi->created_at->format('d F Y, H:i') }} WIB</td>
                                        </tr>
                                    </table>
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
                                                <td><span class="badge bg-secondary">{{ $reservasi->kamar->tipe_kamar }}</span></td>
                                                <td>{{ $checkIn->format('d M Y') }}</td>
                                                <td>{{ $checkOut->format('d M Y') }}</td>
                                                <td>{{ $durasi }} malam</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Ringkasan Pembayaran -->
                            <div class="card bg-light mb-4">
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

                            <!-- Status -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Status Reservasi</h6>
                                    @if($reservasi->status == 'pending' || $reservasi->status == 'menunggu')
                                        <span class="badge bg-warning text-dark fs-6">Menunggu Validasi</span>
                                    @elseif($reservasi->status == 'confirmed' || $reservasi->status == 'disetujui')
                                        <span class="badge bg-success fs-6">Disetujui</span>
                                    @elseif($reservasi->status == 'selesai')
                                        <span class="badge bg-info fs-6">Selesai</span>
                                    @elseif($reservasi->status == 'dibatalkan')
                                        <span class="badge bg-secondary fs-6">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Ditolak</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6>Status Pembayaran</h6>
                                    @if($reservasi->payment)
                                        {!! $reservasi->payment->statusBadge !!}
                                    @else
                                        <span class="badge bg-secondary fs-6">Belum Upload</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Bukti Pembayaran & Validasi -->
                        <div class="col-md-5">
                            <h5 class="mb-3"><i class="bi bi-credit-card"></i> Bukti Pembayaran</h5>
                            
                            @if($reservasi->payment && $reservasi->payment->bukti_pembayaran)
                                <!-- Info Pembayaran -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td width="45%"><strong>Metode</strong></td>
                                                <td>{{ $reservasi->payment->metode_pembayaran }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Upload</strong></td>
                                                <td>{{ $reservasi->payment->tanggal_upload ? $reservasi->payment->tanggal_upload->format('d M Y H:i') : '-' }}</td>
                                            </tr>
                                            @if($reservasi->payment->tanggal_validasi)
                                                <tr>
                                                    <td><strong>Tanggal Validasi</strong></td>
                                                    <td>{{ $reservasi->payment->tanggal_validasi->format('d M Y H:i') }}</td>
                                                </tr>
                                            @endif
                                            @if($reservasi->payment->validator)
                                                <tr>
                                                    <td><strong>Divalidasi Oleh</strong></td>
                                                    <td>{{ $reservasi->payment->validator->name }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                <!-- Bukti Transfer -->
                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <h6 class="mb-3">Bukti Transfer</h6>
                                        <img src="{{ asset('storage/' . $reservasi->payment->bukti_pembayaran) }}" 
                                             alt="Bukti Pembayaran" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 400px; cursor: pointer;"
                                             onclick="window.open(this.src, '_blank')">
                                        <p class="text-muted mt-2 mb-0"><small><i class="bi bi-zoom-in"></i> Klik untuk memperbesar</small></p>
                                    </div>
                                </div>

                                <!-- Form Validasi -->
                                @if($reservasi->payment->status == 'menunggu_validasi')
                                    <div class="card border-primary shadow-sm">
                                        <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <h6 class="mb-0"><i class="bi bi-shield-check"></i> Validasi Pembayaran</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert" style="background: #e3f2fd; border-left: 4px solid #2196f3; color: #1976d2;">
                                                <i class="bi bi-info-circle-fill"></i> <strong>Periksa bukti pembayaran dengan teliti sebelum menyetujui atau menolak.</strong>
                                            </div>
                                            
                                            <div class="d-grid gap-2">
                                                <form action="/admin/reservasi/{{ $reservasi->id }}/approve" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn w-100 btn-lg fw-bold" 
                                                            style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%); color: white; border: none; box-shadow: 0 4px 6px rgba(76, 175, 80, 0.3);"
                                                            onclick="return confirm('Apakah Anda yakin ingin MENERIMA pembayaran ini?\n\nReservasi akan dikonfirmasi.')">
                                                        <i class="bi bi-check-circle-fill"></i> Terima Pembayaran
                                                    </button>
                                                </form>
                                                
                                                <form action="/admin/reservasi/{{ $reservasi->id }}/reject" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn w-100 btn-lg fw-bold" 
                                                            style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white; border: none; box-shadow: 0 4px 6px rgba(244, 67, 54, 0.3);"
                                                            onclick="return confirm('Apakah Anda yakin ingin MENOLAK pembayaran ini?\n\nTamu harus upload ulang bukti pembayaran.')">
                                                        <i class="bi bi-x-circle-fill"></i> Tolak Pembayaran
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($reservasi->payment->status == 'diterima')
                                    <div class="alert alert-success">
                                        <h6 class="alert-heading"><i class="bi bi-check-circle-fill"></i> Pembayaran Diterima</h6>
                                        @if($reservasi->payment->catatan_admin)
                                            <p class="mb-0">{{ $reservasi->payment->catatan_admin }}</p>
                                        @endif
                                    </div>
                                @elseif($reservasi->payment->status == 'ditolak')
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading"><i class="bi bi-x-circle-fill"></i> Pembayaran Ditolak</h6>
                                        @if($reservasi->payment->catatan_admin)
                                            <p class="mb-0">{{ $reservasi->payment->catatan_admin }}</p>
                                        @endif
                                    </div>
                                @endif

                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> <strong>Tamu belum mengupload bukti pembayaran.</strong>
                                    <p class="mb-0 mt-2"><small>Menunggu tamu melakukan upload bukti transfer.</small></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-borderless td {
        padding: 0.5rem 0.5rem;
    }
</style>
@endsection
