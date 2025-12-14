@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-credit-card"></i> Pembayaran Reservasi</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @php
                        $checkIn = new \DateTime($reservasi->check_in);
                        $checkOut = new \DateTime($reservasi->check_out);
                        $jumlahHari = $checkOut->diff($checkIn)->days;
                        $totalBayar = $jumlahHari * $reservasi->kamar->harga;
                    @endphp

                    <div class="row">
                        <!-- Detail Reservasi & Pembayaran -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary"><i class="bi bi-info-circle"></i> Detail Reservasi</h5>
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td width="40%"><strong>Kamar</strong></td>
                                            <td>{{ $reservasi->kamar->nama_kamar }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tipe</strong></td>
                                            <td><span class="badge bg-secondary">{{ $reservasi->kamar->tipe_kamar }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Check-in</strong></td>
                                            <td>{{ $checkIn->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Check-out</strong></td>
                                            <td>{{ $checkOut->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Durasi</strong></td>
                                            <td>{{ $jumlahHari }} malam</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga/Malam</strong></td>
                                            <td>Rp {{ number_format($reservasi->kamar->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div class="text-center">
                                        <h4 class="text-success mb-0">
                                            <strong>Total Pembayaran</strong><br>
                                            <span class="display-6">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Transfer -->
                            <div class="alert alert-warning border-warning">
                                <h6 class="alert-heading"><i class="bi bi-bank"></i> Informasi Rekening Transfer</h6>
                                <hr>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="35%"><strong>Bank</strong></td>
                                        <td>: BCA</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Rekening</strong></td>
                                        <td>: <code class="text-dark">1234567890</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Atas Nama</strong></td>
                                        <td>: Hotel D'Mas</td>
                                    </tr>
                                </table>
                                <div class="alert alert-info mt-3 mb-0">
                                    <small><i class="bi bi-info-circle"></i> Silakan transfer sesuai total pembayaran di atas, kemudian upload bukti transfer.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Upload Bukti -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary"><i class="bi bi-upload"></i> Upload Bukti Pembayaran</h5>
                            
                            <form action="{{ route('tamu.payment.upload', $reservasi->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="metode_pembayaran" class="form-label"><strong>Metode Pembayaran</strong></label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select form-select-lg @error('metode_pembayaran') is-invalid @enderror" required>
                                        <option value="">-- Pilih Metode Pembayaran --</option>
                                        <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                        <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                        <option value="Transfer Bank BNI">Transfer Bank BNI</option>
                                        <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                        <option value="E-Wallet (OVO/DANA/GoPay)">E-Wallet (OVO/DANA/GoPay)</option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="bukti_pembayaran" class="form-label"><strong>Bukti Pembayaran</strong></label>
                                    <input type="file" class="form-control form-control-lg @error('bukti_pembayaran') is-invalid @enderror" 
                                           id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
                                    <div class="form-text">
                                        <i class="bi bi-image"></i> Format: JPG, JPEG, PNG (Max: 2MB)
                                    </div>
                                    @error('bukti_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preview Image -->
                                <div class="mb-4" id="previewContainer" style="display: none;">
                                    <label class="form-label"><strong>Preview Bukti Pembayaran</strong></label>
                                    <div class="text-center border rounded p-3 bg-light">
                                        <img id="preview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                    </div>
                                </div>

                                <!-- Konfirmasi -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="konfirmasi" required>
                                    <label class="form-check-label" for="konfirmasi">
                                        Saya menyatakan bahwa data pembayaran yang saya upload adalah <strong>benar dan valid</strong>
                                    </label>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                        <i class="bi bi-check-circle"></i> Konfirmasi Pembayaran
                                    </button>
                                    <a href="{{ route('tamu.reservasi.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview image before upload
    const buktiInput = document.getElementById('bukti_pembayaran');
    const previewImg = document.getElementById('preview');
    const previewContainer = document.getElementById('previewContainer');
    const konfirmasiCheck = document.getElementById('konfirmasi');
    const submitBtn = document.getElementById('submitBtn');
    
    buktiInput.addEventListener('change', function(e) {
        const [file] = this.files;
        if (file) {
            previewImg.src = URL.createObjectURL(file);
            previewContainer.style.display = 'block';
            checkSubmitButton();
        }
    });

    konfirmasiCheck.addEventListener('change', function() {
        checkSubmitButton();
    });

    function checkSubmitButton() {
        if (buktiInput.files.length > 0 && konfirmasiCheck.checked) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Konfirmasi sebelum submit
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin data pembayaran sudah benar?')) {
            e.preventDefault();
        }
    });
</script>

<style>
    .table-borderless td {
        padding: 0.5rem 0.5rem;
    }
</style>
@endsection
