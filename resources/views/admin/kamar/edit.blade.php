@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil"></i> Edit Kamar
                </div>

                <div class="card-body">
                    <form method="POST" action="/admin/kamar/{{ $kamar->id }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_kamar" class="form-label">Nama Kamar</label>
                            <input type="text" class="form-control @error('nama_kamar') is-invalid @enderror" 
                                   id="nama_kamar" name="nama_kamar" value="{{ old('nama_kamar', $kamar->nama_kamar) }}" required>
                            @error('nama_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe Kamar</label>
                            <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                <option value="">Pilih Tipe</option>
                                <option value="Standard" {{ old('tipe', $kamar->tipe) == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Deluxe" {{ old('tipe', $kamar->tipe) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="Suite" {{ old('tipe', $kamar->tipe) == 'Suite' ? 'selected' : '' }}>Suite</option>
                                <option value="Presidential Suite" {{ old('tipe', $kamar->tipe) == 'Presidential Suite' ? 'selected' : '' }}>Presidential Suite</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga per Malam (Rp)</label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                   id="harga" name="harga" value="{{ old('harga', $kamar->harga) }}" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_bed" class="form-label">Jumlah Bed</label>
                            <input type="number" class="form-control @error('jumlah_bed') is-invalid @enderror" 
                                   id="jumlah_bed" name="jumlah_bed" value="{{ old('jumlah_bed', $kamar->jumlah_bed) }}" required>
                            @error('jumlah_bed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Kamar (Opsional - Kosongkan jika tidak ingin mengubah)</label>
                            @if($kamar->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$kamar->foto) }}" alt="{{ $kamar->nama_kamar }}" 
                                         class="img-thumbnail" style="max-width: 300px;">
                                    <p class="text-muted small mt-1">Foto saat ini</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/kamar" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
