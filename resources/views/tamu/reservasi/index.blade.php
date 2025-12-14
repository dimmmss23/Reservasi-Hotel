@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Reservasi Saya</h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @forelse($data as $reservasi)
                        @php
                            $checkIn = \Carbon\Carbon::parse($reservasi->check_in);
                            $checkOut = \Carbon\Carbon::parse($reservasi->check_out);
                            $durasi = $checkIn->diffInDays($checkOut);
                        @endphp
                        
                        <div class="card mb-3 border-start border-4 
                            @if($reservasi->status == 'disetujui') border-success 
                            @elseif($reservasi->status == 'selesai') border-info 
                            @elseif($reservasi->status == 'ditolak') border-danger 
                            @elseif($reservasi->status == 'dibatalkan') border-secondary 
                            @else border-warning @endif">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($reservasi->kamar->foto)
                                            <img src="{{ asset('storage/'.$reservasi->kamar->foto) }}" 
                                                 alt="{{ $reservasi->kamar->nama_kamar }}" 
                                                 class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                                 style="height: 150px;">
                                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h5 class="fw-bold">{{ $reservasi->kamar->nama_kamar }}</h5>
                                        <span class="badge mb-2" style="background: #D4AF37; color: white;">{{ $reservasi->kamar->tipe }}</span>
                                        
                                        <div class="mt-2">
                                            <p class="mb-1">
                                                <i class="bi bi-calendar-event text-primary"></i>
                                                <strong>Check In:</strong> {{ $checkIn->format('d M Y') }}
                                            </p>
                                            <p class="mb-1">
                                                <i class="bi bi-calendar-event text-danger"></i>
                                                <strong>Check Out:</strong> {{ $checkOut->format('d M Y') }}
                                            </p>
                                            <p class="mb-1">
                                                <i class="bi bi-moon-stars"></i>
                                                <strong>Durasi:</strong> {{ $durasi }} malam
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 text-end">
                                        <h4 class="text-success fw-bold">
                                            Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                        </h4>
                                        <p class="text-muted small mb-2">Total Harga</p>
                                        
                                        @if($reservasi->status == 'menunggu' || $reservasi->status == 'pending')
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-clock-history"></i> Menunggu Konfirmasi
                                            </span>
                                        @elseif($reservasi->status == 'disetujui')
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-check-circle"></i> Disetujui
                                            </span>
                                        @elseif($reservasi->status == 'selesai')
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-check-circle-fill"></i> Selesai (Check Out)
                                            </span>
                                        @elseif($reservasi->status == 'dibatalkan')
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-x-octagon"></i> Dibatalkan
                                            </span>
                                        @elseif($reservasi->status == 'ditolak')
                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-dark fs-6">
                                                <i class="bi bi-question-circle"></i> {{ ucfirst($reservasi->status) }}
                                            </span>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Dibuat: {{ $reservasi->created_at->format('d M Y') }}
                                            </small>
                                        </div>

                                        @if(in_array($reservasi->status, ['pending', 'menunggu']))
                                            <form action="/reservasi/{{ $reservasi->id }}/cancel" method="POST" class="mt-2"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                    <i class="bi bi-x-circle"></i> Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 5rem; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">Belum ada reservasi</h5>
                            <p class="text-muted">Anda belum melakukan reservasi kamar</p>
                            <a href="/kamar" class="btn btn-primary mt-3">
                                <i class="bi bi-search"></i> Cari Kamar
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
