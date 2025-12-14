@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Validasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informasi Tamu & Reservasi -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Informasi Tamu</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th width="40%">Nama Tamu</th>
                                    <td>{{ $payment->reservasi->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $payment->reservasi->user->email }}</td>
                                </tr>
                            </table>

                            <h6 class="mb-3 mt-4">Informasi Reservasi</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th width="40%">Kamar</th>
                                    <td>{{ $payment->reservasi->kamar->nama_kamar }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Kamar</th>
                                    <td>{{ $payment->reservasi->kamar->tipe_kamar }}</td>
                                </tr>
                                <tr>
                                    <th>Check-in</th>
                                    <td>{{ \Carbon\Carbon::parse($payment->reservasi->check_in)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Check-out</th>
                                    <td>{{ \Carbon\Carbon::parse($payment->reservasi->check_out)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Harga/Malam</th>
                                    <td>Rp {{ number_format($payment->reservasi->kamar->harga, 0, ',', '.') }}</td>
                                </tr>
                            </table>

                            <h6 class="mb-3 mt-4">Informasi Pembayaran</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th width="40%">Total Bayar</th>
                                    <td><strong class="text-primary">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Metode</th>
                                    <td>{{ $payment->metode_pembayaran }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!! $payment->statusBadge !!}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <td>{{ $payment->tanggal_upload ? $payment->tanggal_upload->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Bukti Pembayaran -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Bukti Pembayaran</h6>
                            @if($payment->bukti_pembayaran)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-thumbnail mb-3" 
                                         style="max-width: 100%; cursor: pointer;"
                                         onclick="window.open(this.src, '_blank')">
                                    <p class="text-muted"><small>Klik gambar untuk memperbesar</small></p>
                                </div>
                            @else
                                <div class="alert alert-warning">Bukti pembayaran belum diupload</div>
                            @endif

                            <!-- Form Validasi -->
                            @if($payment->status == 'menunggu_validasi')
                                <hr>
                                <h6 class="mb-3">Validasi Pembayaran</h6>
                                <form action="{{ route('admin.payment.process', $payment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Status Validasi</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="">Pilih Status</option>
                                            <option value="diterima">Terima Pembayaran</option>
                                            <option value="ditolak">Tolak Pembayaran</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan (Opsional)</label>
                                        <textarea name="catatan_admin" 
                                                  class="form-control @error('catatan_admin') is-invalid @enderror" 
                                                  rows="3" 
                                                  placeholder="Masukkan catatan jika diperlukan..."></textarea>
                                        @error('catatan_admin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">Kembali</a>
                                        <button type="submit" class="btn btn-primary">Proses Validasi</button>
                                    </div>
                                </form>
                            @else
                                <hr>
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Status: {{ ucfirst($payment->status) }}</h6>
                                    @if($payment->tanggal_validasi)
                                        <p><strong>Divalidasi pada:</strong> {{ $payment->tanggal_validasi->format('d M Y H:i') }}</p>
                                    @endif
                                    @if($payment->validator)
                                        <p><strong>Oleh:</strong> {{ $payment->validator->name }}</p>
                                    @endif
                                    @if($payment->catatan_admin)
                                        <p class="mb-0"><strong>Catatan:</strong> {{ $payment->catatan_admin }}</p>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
