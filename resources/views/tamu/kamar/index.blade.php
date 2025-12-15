@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="text-center">
                <h2 class="fw-bold">Kamar Tersedia</h2>
                <p class="text-muted">Pilih kamar yang sesuai dengan kebutuhan Anda</p>
            </div>
        </div>
    </div>

    <!-- Kamar Cards -->
    <div class="row">
        @forelse($data as $kamar)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm hover-shadow d-flex flex-column">
                    @if($kamar->foto)
                        <img src="{{ asset('storage/'.$kamar->foto) }}" class="card-img-top" 
                             alt="{{ $kamar->nama_kamar }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $kamar->nama_kamar }}</h5>
                            <span class="badge" style="background: #D4AF37; color: white;">{{ $kamar->tipe }}</span>
                        </div>
                        
                        <!-- Status Ketersediaan -->
                        @if($kamar->isReserved())
                            @php
                                $activeReservations = $kamar->activeReservations();
                                $reservationCount = $activeReservations->count();
                            @endphp
                            <div class="alert alert-warning py-2 px-2 mb-2" style="font-size: 0.85rem;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span><i class="bi bi-exclamation-triangle"></i> <strong>Sudah Direservasi</strong></span>
                                    <span class="badge bg-danger">{{ $reservationCount }} periode</span>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary w-100 mt-1" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#reservasi-{{ $kamar->id }}" 
                                        aria-expanded="false">
                                    <i class="bi bi-calendar-event"></i> Lihat Periode Reservasi
                                </button>
                                <div class="collapse mt-2" id="reservasi-{{ $kamar->id }}">
                                    <div class="card card-body p-2" style="font-size: 0.75rem; background-color: #fff3cd;">
                                        <strong class="mb-1">Tidak tersedia pada:</strong>
                                        @foreach($activeReservations as $reservation)
                                            @php
                                                $checkInDate = \Carbon\Carbon::parse($reservation->check_in)->format('d M Y');
                                                $checkOutDate = \Carbon\Carbon::parse($reservation->check_out)->format('d M Y');
                                            @endphp
                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                <i class="bi bi-calendar-x text-danger"></i>
                                                <span>{{ $checkInDate }} - {{ $checkOutDate }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success py-1 px-2 mb-2" style="font-size: 0.85rem;">
                                <i class="bi bi-check-circle"></i> <strong>Tersedia Sekarang</strong>
                            </div>
                        @endif
                        
                        <p class="card-text text-muted">{{ Str::limit($kamar->deskripsi, 80) }}</p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="bi bi-people"></i> {{ $kamar->jumlah_bed }} Bed
                            </small>
                        </div>
                        
                        <!-- Spacer untuk push tombol ke bawah -->
                        <div class="mt-auto">
                            <div class="mb-3">
                                <h5 class="mb-0" style="color: #D4AF37; font-weight: 700;">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h5>
                                <small class="text-muted">per malam</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="/kamar/{{ $kamar->id }}" class="btn btn-outline-gold flex-fill">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="/kamar/{{ $kamar->id }}/pesan" class="btn btn-gold flex-fill">
                                    <i class="bi bi-calendar-check"></i> Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Tidak ada kamar tersedia saat ini</h5>
                    <p>Silakan cek kembali nanti</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.hover-shadow {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
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

.btn-outline-gold {
    background: white;
    border: 2px solid #D4AF37;
    color: #D4AF37;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-outline-gold:hover {
    background: #D4AF37;
    border-color: #D4AF37;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}
</style>
<link rel="stylesheet" href="{{ asset('css/tamu-kamar.css') }}">
@endsection
