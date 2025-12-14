@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
        <span class="text-muted">Selamat datang, {{ Auth::user()->name }}</span>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Kamar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Kamar</p>
                            <h3 class="mb-0">{{ $totalKamar }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-door-closed text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Reservasi -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Reservasi</p>
                            <h3 class="mb-0">{{ $totalReservasi }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-calendar-check text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menunggu Validasi -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Menunggu Validasi</p>
                            <h3 class="mb-0">{{ $reservasiPending }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Pendapatan</p>
                            <h3 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cash-stack text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tambahan -->
    <div class="row g-3 mb-4">
        <!-- Reservasi Disetujui -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Reservasi Disetujui</p>
                            <h4 class="mb-0">{{ $reservasiDisetujui }}</h4>
                        </div>
                        <i class="bi bi-check-circle text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Tamu -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total User</p>
                            <h4 class="mb-0">{{ $totalTamu }}</h4>
                        </div>
                        <i class="bi bi-people text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Kamar Per Tipe -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Kamar Per Tipe</h5>
                </div>
                <div class="card-body">
                    @forelse($kamarPerTipe as $tipe)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $tipe->tipe }}</h6>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $tipe->total }} kamar</span>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Belum ada data kamar</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Reservasi Terbaru -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Reservasi Terbaru</h5>
                        <a href="/admin/reservasi" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($reservasiTerbaru as $reservasi)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $reservasi->kamar->nama_kamar }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $reservasi->user->name }} | 
                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($reservasi->check_in)->format('d M Y') }}
                                </small>
                            </div>
                            <div class="text-end">
                                @if($reservasi->status == 'pending' || $reservasi->status == 'menunggu')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($reservasi->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($reservasi->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($reservasi->status) }}</span>
                                @endif
                                <br>
                                <small class="text-muted">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Belum ada reservasi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="/admin/kamar/create" class="btn btn-outline-primary w-100">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Kamar
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/reservasi" class="btn btn-outline-info w-100">
                        <i class="bi bi-list-check me-2"></i>Kelola Reservasi
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="/admin/user" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-people me-2"></i>Kelola User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
