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
                        <div class="alert alert-danger mb-3">
                            <i class="bi bi-x-circle"></i> <strong>Kamar Tidak Tersedia</strong><br>
                            <small>Kamar ini sedang direservasi.</small>
                        </div>
                    @else
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle"></i> <strong>Kamar Tersedia</strong><br>
                            <small>Kamar ini dapat direservasi.</small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <h5 style="color: #D4AF37; font-weight: 700;">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h5>
                        <small class="text-muted">per malam</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-people"></i> Kapasitas:</h6>
                        <p>{{ $kamar->jumlah_bed }} Bed</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-info-circle"></i> Deskripsi:</h6>
                        <p class="text-muted">{{ $kamar->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    
                    <div class="d-grid">
                        <a href="/kamar" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kamar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    
    checkIn.addEventListener('change', function() {
        const minCheckOut = new Date(this.value);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        checkOut.min = minCheckOut.toISOString().split('T')[0];
        if (checkOut.value && checkOut.value <= this.value) {
            checkOut.value = '';
        }
    });
});
</script>
@endsection
