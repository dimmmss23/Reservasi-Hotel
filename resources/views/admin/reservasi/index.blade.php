@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Kelola Reservasi</h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Tamu</th>
                                    <th>Kamar</th>
                                    <th>Check In / Check Out</th>
                                    <th>Durasi</th>
                                    <th>Total Harga</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Reservasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key => $reservasi)
                                    @php
                                        $checkIn = \Carbon\Carbon::parse($reservasi->check_in);
                                        $checkOut = \Carbon\Carbon::parse($reservasi->check_out);
                                        $durasi = $checkIn->diffInDays($checkOut);
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>{{ $reservasi->user->name }}</strong><br>
                                            <small class="text-muted">{{ $reservasi->user->email }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $reservasi->kamar->nama_kamar }}</strong><br>
                                            <span class="badge" style="background: #D4AF37; color: white;">{{ $reservasi->kamar->tipe }}</span>
                                        </td>
                                        <td>
                                            <small><i class="bi bi-calendar-event"></i> {{ $checkIn->format('d M Y') }}</small><br>
                                            <small><i class="bi bi-calendar-event"></i> {{ $checkOut->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ $durasi }} malam</td>
                                        <td><strong class="text-success">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($reservasi->payment)
                                                {!! $reservasi->payment->statusBadge !!}
                                            @else
                                                <span class="badge bg-secondary">Belum Upload</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reservasi->status == 'menunggu' || $reservasi->status == 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @elseif($reservasi->status == 'disetujui' || $reservasi->status == 'confirmed')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($reservasi->status == 'selesai')
                                                <span class="badge bg-info">Selesai</span>
                                            @elseif($reservasi->status == 'dibatalkan')
                                                <span class="badge bg-secondary">Dibatalkan</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(in_array($reservasi->status, ['menunggu', 'pending']))
                                                @if($reservasi->payment && $reservasi->payment->status == 'menunggu_validasi')
                                                    <a href="{{ route('admin.reservasi.show', $reservasi->id) }}" class="btn btn-sm btn-primary w-100 mb-1">
                                                        <i class="bi bi-eye"></i> Lihat & Validasi
                                                    </a>
                                                @else
                                                    <small class="text-muted">Menunggu pembayaran</small>
                                                @endif
                                            @elseif(in_array($reservasi->status, ['disetujui', 'confirmed']))
                                                <a href="{{ route('admin.reservasi.show', $reservasi->id) }}" class="btn btn-sm btn-info w-100 mb-1">
                                                    <i class="bi bi-eye"></i> Lihat Detail
                                                </a>
                                                <form action="/admin/reservasi/{{ $reservasi->id }}/checkout" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning w-100" 
                                                            onclick="return confirm('Checkout reservasi ini? Kamar akan tersedia kembali.')">
                                                        <i class="bi bi-box-arrow-right"></i> Checkout
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.reservasi.show', $reservasi->id) }}" class="btn btn-sm btn-outline-secondary w-100">
                                                    <i class="bi bi-eye"></i> Lihat Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Belum ada reservasi</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
