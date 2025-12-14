@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Validasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filter Status -->
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-primary">Semua</a>
                            <a href="{{ route('admin.payment.index', ['status' => 'menunggu_validasi']) }}" class="btn btn-outline-warning">Menunggu Validasi</a>
                            <a href="{{ route('admin.payment.index', ['status' => 'diterima']) }}" class="btn btn-outline-success">Diterima</a>
                            <a href="{{ route('admin.payment.index', ['status' => 'ditolak']) }}" class="btn btn-outline-danger">Ditolak</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tamu</th>
                                    <th>Kamar</th>
                                    <th>Check-in / Check-out</th>
                                    <th>Total Bayar</th>
                                    <th>Metode</th>
                                    <th>Tanggal Upload</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $index => $payment)
                                    <tr>
                                        <td>{{ $payments->firstItem() + $index }}</td>
                                        <td>{{ $payment->reservasi->user->name }}</td>
                                        <td>{{ $payment->reservasi->kamar->nama_kamar }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($payment->reservasi->check_in)->format('d/m/Y') }}
                                            <br>
                                            {{ \Carbon\Carbon::parse($payment->reservasi->check_out)->format('d/m/Y') }}
                                        </td>
                                        <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td>{{ $payment->metode_pembayaran }}</td>
                                        <td>{{ $payment->tanggal_upload ? $payment->tanggal_upload->format('d/m/Y H:i') : '-' }}</td>
                                        <td>{!! $payment->statusBadge !!}</td>
                                        <td>
                                            <a href="{{ route('admin.payment.validate', $payment->id) }}" 
                                               class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
