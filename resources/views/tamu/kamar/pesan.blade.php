@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Foto Kamar -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                @if($kamar->foto)
                    <img src="{{ asset('storage/'.$kamar->foto) }}" class="card-img-top" 
                         alt="{{ $kamar->nama_kamar }}" style="height: 400px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                         style="height: 400px;">
                        <i class="bi bi-image" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Kamar & Form Reservasi -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="fw-bold mb-0">{{ $kamar->nama_kamar }}</h3>
                        <span class="badge fs-6" style="background: #D4AF37; color: white;">{{ $kamar->tipe }}</span>
                    </div>
                    
                    <hr>
                    
                    <!-- Status Ketersediaan -->
                    @if($isReserved)
                        @php
                            $activeReservations = $kamar->activeReservations();
                            $reservationCount = $activeReservations->count();
                        @endphp
                        <div class="alert alert-warning mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Kamar Sudah Direservasi</h6>
                                <span class="badge bg-danger">{{ $reservationCount }} periode</span>
                            </div>
                        </div>
                        
                        <div class="card border-warning mb-3">
                            <div class="card-header bg-warning bg-opacity-10">
                                <strong><i class="bi bi-calendar-x"></i> Periode yang Tidak Tersedia</strong>
                            </div>
                            <div class="card-body">
                                @foreach($activeReservations as $index => $reservation)
                                    @php
                                        $checkInDate = \Carbon\Carbon::parse($reservation->check_in)->format('d M Y');
                                        $checkOutDate = \Carbon\Carbon::parse($reservation->check_out)->format('d M Y');
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 border-start border-danger border-3 bg-light">
                                        <div>
                                            <i class="bi bi-calendar-event text-danger"></i>
                                            <strong>{{ $checkInDate }}</strong> s/d <strong>{{ $checkOutDate }}</strong>
                                        </div>
                                        <span class="badge bg-secondary">Periode {{ $index + 1 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <small><i class="bi bi-info-circle"></i> Pilih tanggal di luar periode yang sudah direservasi.</small>
                        </div>
                    @else
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle"></i> <strong>Kamar Tersedia</strong><br>
                            <small>Silakan pilih tanggal untuk melakukan reservasi!</small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <h5 class="text-muted mb-2">
                            <i class="bi bi-cash-coin"></i> Harga per Malam
                        </h5>
                        <h4 class="fw-bold" style="color: #D4AF37;">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h4>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bi bi-door-open"></i> Jumlah Bed</h6>
                        <p class="mb-0">{{ $kamar->jumlah_bed }} Bed</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted"><i class="bi bi-card-text"></i> Deskripsi</h6>
                        <p class="text-muted">{{ $kamar->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Reservasi -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Form Reservasi</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="/reservasi" method="POST">
                        @csrf
                        <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                        
                        @if($isReserved)
                        @php
                            $activeReservations = $kamar->activeReservations();
                        @endphp
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Perhatian!</strong><br>
                            Kamar ini sudah direservasi pada periode berikut:<br>
                            @foreach($activeReservations as $reservation)
                                <span class="badge bg-danger me-1 mt-1">{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }} - {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</span>
                            @endforeach
                            <br><small>Silakan pilih tanggal di luar periode tersebut.</small>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="check_in" class="form-label">
                                <i class="bi bi-calendar-event"></i> Tanggal Check-in
                            </label>
                            <input type="date" 
                                   class="form-control @error('check_in') is-invalid @enderror" 
                                   id="check_in" 
                                   name="check_in" 
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('check_in') }}" 
                                   required>
                            @error('check_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="check_out" class="form-label">
                                <i class="bi bi-calendar-event"></i> Tanggal Check-out
                            </label>
                            <input type="date" 
                                   class="form-control @error('check_out') is-invalid @enderror" 
                                   id="check_out" 
                                   name="check_out" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   value="{{ old('check_out') }}" 
                                   required>
                            @error('check_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info mb-3">
                            <h6 class="mb-2"><i class="bi bi-info-circle"></i> <strong>Informasi Penting:</strong></h6>
                            <ul class="mb-0" style="font-size: 0.9rem;">
                                <li>Setelah mengirim reservasi, Anda akan <strong>langsung diarahkan ke halaman pembayaran</strong></li>
                                <li>Harap siapkan bukti transfer untuk melengkapi pembayaran</li>
                                <li>Total harga akan dihitung otomatis berdasarkan jumlah malam</li>
                                <li>Reservasi akan diproses setelah pembayaran divalidasi admin</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-arrow-right-circle"></i> Lanjut ke Pembayaran
                            </button>
                            <a href="/kamar" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kamar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
