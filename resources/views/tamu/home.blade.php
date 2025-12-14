@extends('layouts.app')

@section('title', 'Home - D\'Mas Hotel')

@section('content')
<!-- Hero Section -->
<div class="hero-section" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/bg-hotel.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="lead mb-4">Temukan kenyamanan dan kemewahan di D'Mas Hotel. Pilih kamar impian Anda dan nikmati pengalaman menginap yang tak terlupakan.</p>
                <div class="d-flex gap-3">
                    <a href="/kamar" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-search me-2"></i>Cari Kamar
                    </a>
                    <a href="/reservasi/saya" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-calendar-event me-2"></i>Reservasi Saya
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <i class="bi bi-house-heart" style="font-size: 200px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="container my-5">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $totalReservasi }}</h4>
                    <p class="text-muted mb-0">Total Reservasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $reservasiPending }}</h4>
                    <p class="text-muted mb-0">Menunggu Konfirmasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $reservasiDisetujui }}</h4>
                    <p class="text-muted mb-0">Reservasi Disetujui</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-door-closed text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $totalKamar }}</h4>
                    <p class="text-muted mb-0">Kamar Tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Rooms -->
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Kamar Pilihan Kami</h2>
        <p class="text-muted">Pilih kamar yang sesuai dengan kebutuhan Anda</p>
    </div>

    <div class="row g-4">
        @forelse($kamarTerbaru as $kamar)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    @if($kamar->foto)
                        <img src="{{ asset('storage/'.$kamar->foto) }}" class="card-img-top" alt="{{ $kamar->nama_kamar }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $kamar->nama_kamar }}</h5>
                            <span class="badge" style="background: #D4AF37; color: white;">{{ $kamar->tipe }}</span>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-people me-1"></i>{{ $kamar->jumlah_bed }} bed
                        </p>
                        <p class="card-text text-muted small">{{ Str::limit($kamar->deskripsi, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted d-block">Mulai dari</small>
                                <h5 class="mb-0" style="color: #D4AF37; font-weight: 700;">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h5>
                                <small class="text-muted">/malam</small>
                            </div>
                            <a href="/kamar/{{ $kamar->id }}/pesan" class="btn btn-gold">
                                Pesan <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada kamar tersedia</p>
            </div>
        @endforelse
    </div>

    @if($kamarTerbaru->count() > 0)
        <div class="text-center mt-4">
            <a href="/kamar" class="btn btn-gold btn-lg" style="padding: 12px 32px;">
                Lihat Semua Kamar <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    @endif
</div>

<!-- Recent Reservations -->
@if($reservasiTerbaru->count() > 0)
<div class="container my-5">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Reservasi Terbaru Anda</h5>
                <a href="/reservasi/saya" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kamar</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasiTerbaru as $reservasi)
                            <tr>
                                <td>
                                    <strong>{{ $reservasi->kamar->nama_kamar }}</strong><br>
                                    <small class="text-muted">{{ $reservasi->kamar->tipe }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($reservasi->check_in)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservasi->check_out)->format('d M Y') }}</td>
                                <td>
                                    <strong class="text-primary">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($reservasi->status == 'pending' || $reservasi->status == 'menunggu')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($reservasi->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($reservasi->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($reservasi->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($reservasi->payment)
                                        <a href="{{ route('tamu.payment.show', $reservasi->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-receipt"></i> Invoice
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Features Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="bg-white rounded-circle d-inline-flex p-4 mb-3 shadow-sm">
                    <i class="bi bi-shield-check text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Aman & Terpercaya</h5>
                <p class="text-muted">Sistem pembayaran yang aman dan data terlindungi</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white rounded-circle d-inline-flex p-4 mb-3 shadow-sm">
                    <i class="bi bi-headset text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Layanan 24/7</h5>
                <p class="text-muted">Tim customer service siap membantu kapan saja</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white rounded-circle d-inline-flex p-4 mb-3 shadow-sm">
                    <i class="bi bi-star-fill text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Kualitas Terbaik</h5>
                <p class="text-muted">Kamar berkualitas dengan fasilitas lengkap</p>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.btn-gold {
    background: linear-gradient(135deg, #D4AF37, #C9A227);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-gold:hover {
    background: linear-gradient(135deg, #C9A227, #D4AF37);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}
</style>
@endsection
