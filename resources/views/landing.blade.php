<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Mas Hotel - Luxury Beachfront Resort</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">D'Mas Hotel</div>
        <div class="nav-links">
            <a href="#rooms">Kamar</a>
            <a href="#features">Fasilitas</a>
            <a href="#about">Tentang Kami</a>
            <a href="/login">Masuk</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>D'Mas Hotel</h1>
            <p>Luxury Beachfront Resort Experience</p>
            <p style="font-size: 1.1rem;">Nikmati kemewahan tepi pantai dengan pemandangan laut yang menakjubkan</p>
            <div style="margin-top: 30px;">
                <a href="#rooms" class="btn-hero">Lihat Kamar</a>
                <a href="/login" class="btn-hero secondary">Pesan Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Fasilitas Premium</h2>
        <p class="section-subtitle">Nikmati pengalaman menginap yang tak terlupakan dengan fasilitas kelas dunia</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🏊‍♂️</div>
                <h3>Infinity Pool</h3>
                <p>Kolam renang infinity dengan pemandangan laut yang spektakuler</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏖️</div>
                <h3>Private Beach</h3>
                <p>Akses eksklusif ke pantai pribadi dengan pasir putih yang indah</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🍽️</div>
                <h3>Fine Dining</h3>
                <p>Restoran mewah dengan menu internasional dan lokal pilihan</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💆‍♀️</div>
                <h3>Spa & Wellness</h3>
                <p>Spa premium untuk relaksasi dan perawatan tubuh Anda</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏋️</div>
                <h3>Fitness Center</h3>
                <p>Pusat kebugaran modern dengan peralatan lengkap</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🅿️</div>
                <h3>Free Parking</h3>
                <p>Parkir gratis dan aman untuk kenyamanan Anda</p>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section class="rooms" id="rooms">
        <h2 class="section-title">Kamar & Suite Kami</h2>
        <p class="section-subtitle">Pilih kamar yang sempurna untuk pengalaman menginap Anda</p>
        
        <div class="rooms-grid">
            @foreach($kamars as $kamar)
            <div class="room-card">
                <div class="room-image">
                    @if($kamar->foto)
                        <img src="{{ asset('storage/'.$kamar->foto) }}" alt="{{ $kamar->nama_kamar }}">
                    @else
                        <div class="room-image-placeholder" style="width: 100%; height: 100%;">
                            {{ $kamar->tipe }}
                        </div>
                    @endif
                </div>
                <div class="room-info">
                    <h3>{{ $kamar->nama_kamar }}</h3>
                    <p><span class="badge" style="background: #D4AF37; color: white; padding: 5px 10px; border-radius: 5px;">{{ $kamar->tipe }}</span></p>
                    <p class="price">Rp {{ number_format($kamar->harga, 0, ',', '.') }} <small>/ malam</small></p>
                    <p style="min-height: 60px;">{{ Str::limit($kamar->deskripsi ?? 'Kamar nyaman dengan fasilitas lengkap dan pemandangan indah', 80) }}</p>
                    
                    <div class="room-actions">
                        <a href="#" class="btn-detail" onclick="showRoomDetail({{ $kamar->id }}, '{{ $kamar->nama_kamar }}', '{{ $kamar->tipe }}', '{{ number_format($kamar->harga, 0, ',', '.') }}', '{{ $kamar->jumlah_bed }}', '{{ $kamar->deskripsi ?? 'Tidak ada deskripsi' }}', '{{ $kamar->foto ? asset('storage/'.$kamar->foto) : '' }}'); return false;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                    
                    @auth
                        <a href="/kamar" class="btn-book">Pesan Sekarang</a>
                    @else
                        <a href="/login" class="btn-book">Masuk untuk Memesan</a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <h2 class="section-title">Tentang D'Mas Hotel</h2>
        <p>D'Mas Hotel adalah resort tepi pantai mewah yang menawarkan pengalaman menginap tak terlupakan. Dengan arsitektur modern yang elegan dan pemandangan laut yang menakjubkan, kami berkomitmen memberikan pelayanan terbaik untuk setiap tamu.</p>
        <p>Terletak di lokasi strategis dengan akses langsung ke pantai pribadi, D'Mas Hotel adalah destinasi sempurna untuk liburan romantis, gathering keluarga, atau acara bisnis Anda.</p>
        <p>Dengan lebih dari 200 kamar dan suite yang dirancang dengan sempurna, fasilitas kelas dunia, dan staf profesional yang siap melayani 24/7, kami memastikan setiap momen Anda bersama kami adalah istimewa.</p>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 D'Mas Hotel. Dimas Agung Subayu_23041450144.</p>
    </footer>

    <!-- Modal Detail Kamar -->
    <div id="roomModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; overflow-y: auto;">
        <div style="max-width: 800px; margin: 50px auto; background: white; border-radius: 15px; overflow: hidden; position: relative;">
            <button onclick="closeRoomDetail()" style="position: absolute; top: 20px; right: 20px; background: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 24px; color: #333; z-index: 10; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">×</button>
            
            <div id="modalImage" style="width: 100%; height: 400px; background-size: cover; background-position: center;"></div>
            
            <div style="padding: 40px;">
                <h2 id="modalTitle" style="font-size: 2rem; margin-bottom: 15px; color: #2c3e50;"></h2>
                <p><span id="modalType" style="background: #D4AF37; color: white; padding: 8px 15px; border-radius: 5px; font-weight: 600;"></span></p>
                <p id="modalPrice" style="font-size: 2rem; color: #D4AF37; font-weight: 700; margin: 20px 0;"></p>
                
                <div style="border-top: 2px solid #f0f0f0; padding-top: 20px; margin-top: 20px;">
                    <h3 style="margin-bottom: 15px; color: #2c3e50;">Detail Kamar</h3>
                    <p style="margin-bottom: 10px;"><strong>🛏️ Jumlah Bed:</strong> <span id="modalBed"></span></p>
                    <p style="margin-bottom: 20px;"><strong>📝 Deskripsi:</strong></p>
                    <p id="modalDesc" style="color: #7f8c8d; line-height: 1.8;"></p>
                </div>
                
                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    @auth
                        <a href="/kamar" style="flex: 1; padding: 15px; background: linear-gradient(135deg, #D4AF37, #C9A227); color: white; text-align: center; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 1.1rem;">Pesan Sekarang</a>
                    @else
                        <a href="/login" style="flex: 1; padding: 15px; background: linear-gradient(135deg, #D4AF37, #C9A227); color: white; text-align: center; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 1.1rem;">Masuk untuk Memesan</a>
                    @endauth
                    <button onclick="closeRoomDetail()" style="flex: 1; padding: 15px; background: white; border: 2px solid #ddd; color: #333; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1.1rem;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRoomDetail(id, nama, tipe, harga, bed, deskripsi, foto) {
            document.getElementById('modalTitle').textContent = nama;
            document.getElementById('modalType').textContent = tipe;
            document.getElementById('modalPrice').innerHTML = 'Rp ' + harga + ' <small style="font-size: 1rem; color: #7f8c8d; font-weight: 400;">/ malam</small>';
            document.getElementById('modalBed').textContent = bed + ' Bed';
            document.getElementById('modalDesc').textContent = deskripsi;
            
            if(foto) {
                document.getElementById('modalImage').style.backgroundImage = 'url(' + foto + ')';
            } else {
                document.getElementById('modalImage').style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                document.getElementById('modalImage').style.display = 'flex';
                document.getElementById('modalImage').style.alignItems = 'center';
                document.getElementById('modalImage').style.justifyContent = 'center';
                document.getElementById('modalImage').innerHTML = '<span style="color: white; font-size: 2rem; font-weight: 600;">' + tipe + '</span>';
            }
            
            document.getElementById('roomModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeRoomDetail() {
            document.getElementById('roomModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        document.getElementById('roomModal').addEventListener('click', function(e) {
            if(e.target === this) {
                closeRoomDetail();
            }
        });
    </script>
</body>
</html>
