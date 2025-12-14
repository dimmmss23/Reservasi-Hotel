@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Kamar Baru</h5>
                </div>

                <div class="card-body">
                    <form action="/admin/kamar" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_kamar" class="form-label">Nama Kamar <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kamar') is-invalid @enderror" 
                                   id="nama_kamar" name="nama_kamar" value="{{ old('nama_kamar') }}" 
                                   placeholder="Contoh: Deluxe Room" required>
                            @error('nama_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipe" class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipe') is-invalid @enderror" 
                                        id="tipe" name="tipe" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="Standard" {{ old('tipe') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="Deluxe" {{ old('tipe') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                    <option value="Suite" {{ old('tipe') == 'Suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="Executive" {{ old('tipe') == 'Executive' ? 'selected' : '' }}>Executive</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jumlah_bed" class="form-label">Jumlah Bed <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('jumlah_bed') is-invalid @enderror" 
                                       id="jumlah_bed" name="jumlah_bed" value="{{ old('jumlah_bed') }}" 
                                       min="1" placeholder="Contoh: 2" required>
                                @error('jumlah_bed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga per Malam (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                   id="harga" name="harga" value="{{ old('harga') }}" 
                                   min="0" placeholder="Contoh: 500000" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" 
                                      placeholder="Deskripsikan fasilitas dan keunggulan kamar...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Kamar <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/kamar" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
