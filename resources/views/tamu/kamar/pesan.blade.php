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
                    @if($isReserved && $activeReservation)
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-info-circle"></i> <strong>Kamar Sedang Direservasi</strong><br>
                            <small>
                                Kamar ini sudah direservasi dari 
                                <strong>{{ \Carbon\Carbon::parse($activeReservation->check_in)->format('d M Y') }}</strong> 
                                hingga 
                                <strong>{{ \Carbon\Carbon::parse($activeReservation->check_out)->format('d M Y') }}</strong>.
                                <br>
                                Anda bisa memesan kamar ini untuk tanggal di luar periode tersebut.
                            </small>
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
                        
                        @if($isReserved && $activeReservation)
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Perhatian!</strong><br>
                            Kamar ini sudah direservasi dari <strong>{{ \Carbon\Carbon::parse($activeReservation->check_in)->format('d M Y') }}</strong> 
                            hingga <strong>{{ \Carbon\Carbon::parse($activeReservation->check_out)->format('d M Y') }}</strong>.<br>
                            Silakan pilih tanggal di luar periode tersebut.
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
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Catatan:</strong><br>
                            - Reservasi akan menunggu konfirmasi dari admin<br>
                            - Total harga akan dihitung otomatis berdasarkan jumlah malam<br>
                            - Pastikan tanggal yang dipilih tidak bentrok dengan reservasi lain<br>
                            - Tanggal check-in dan check-out harus benar
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-send"></i> Kirim Reservasi
                            </button>
                            <a href="/kamar" class="btn btn-secondary">
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
