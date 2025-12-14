@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-door-open"></i> Kelola Kamar</h5>
                    <a href="/admin/kamar/create" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Kamar
                    </a>
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
                                    <th>Foto</th>
                                    <th>Nama Kamar</th>
                                    <th>Tipe</th>
                                    <th>Harga/Malam</th>
                                    <th>Jumlah Bed</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key => $kamar)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($kamar->foto)
                                                <img src="{{ asset('storage/'.$kamar->foto) }}" alt="{{ $kamar->nama_kamar }}" 
                                                     class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $kamar->nama_kamar }}</strong></td>
                                        <td><span class="badge" style="background: #D4AF37; color: white;">{{ $kamar->tipe }}</span></td>
                                        <td><strong class="text-success">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</strong></td>
                                        <td>{{ $kamar->jumlah_bed }} Bed</td>
                                        <td>{{ Str::limit($kamar->deskripsi, 50) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/admin/kamar/{{ $kamar->id }}/edit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="/admin/kamar/{{ $kamar->id }}" method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Yakin hapus kamar ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Belum ada data kamar</p>
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
