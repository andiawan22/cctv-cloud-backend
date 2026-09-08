<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Cloud CCTV</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#f8fafc] text-slate-800 font-['Poppins'] antialiased selection:bg-blue-200">

    <!-- Navbar -->
    <nav class="w-full bg-slate-900 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
            
            <!-- Kelompok Logo (Kiri) -->
            <div class="flex items-center gap-4">
                <!-- Logo CCTV -->
                <a href="/" class="flex items-center">
                    <img src="{{ asset('asset/images/logo-new-cctv-removebg-preview-10.png') }}" style="height: 60px; width: auto; object-fit: contain;" alt="Logo Cloud CCTV">
                </a>
                
                <!-- Garis Pemisah Halus -->
                <div style="height: 30px; width: 1px; background-color: #475569;"></div>
                
                <!-- Logo PENS (Tinggi diatur 35px agar seimbang secara visual dengan CCTV yang 45px) -->
                <a href="/" class="flex items-center">
                    <img src="{{ asset('asset/images/logo-pens-removebg-preview-10.png') }}" style="height: 35px; width: auto; object-fit: contain;" alt="Logo PENS">
                </a>
            </div>
            
            <!-- Kelompok Tombol (Kanan) -->
            <div class="flex items-center">
                <!-- Tombol Masuk (Warna teks dikunci ke putih dengan inline style) -->
                <a href="{{ route('login') }}" style="background-color: #eb2525; color: #ffffff; padding: 8px 24px; border-radius: 50px; font-weight: 600; text-decoration: none;" class="hover:bg-blue-700 transition-all shadow-md">
                    Masuk
                </a>
                
                <!-- Tombol Daftar -->
                <a href="{{ route('register') }}" style="background-color: #2563eb; color: #ffffff; padding: 8px 24px; border-radius: 50px; font-weight: 600; text-decoration: none;" class="hover:bg-blue-700 transition-all shadow-md">
                    Daftar
                </a>
            </div>
            
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-6 py-16 md:py-20 flex flex-col md:flex-row items-center gap-12">
        
        <!-- Teks Kiri -->
        <div class="w-full md:w-1/2">
            <div class="inline-block px-4 py-1.5 rounded-full bg-blue-100 text-blue-700 font-medium text-sm mb-6 border border-blue-200">
                Sistem Terpadu V1.0
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-[1.2] mb-6">
                Keamanan Pintar, <br><span class="text-blue-600">Pantauan Tanpa Batas.</span>
            </h1>
            <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-lg">
                Sistem manajemen pemantauan cerdas yang terintegrasi, aman, dan mudah diakses langsung dari perangkat Anda. Lindungi aset berharga Anda 24/7.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 font-semibold shadow-xl shadow-slate-900/20 transition-all transform hover:-translate-y-1">
                    Mulai Pantau Sekarang
                </a>
            </div>
        </div>

        <!-- Gambar Kanan -->
        <div class="w-full md:w-1/2">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-blue-900/10 aspect-video border-[6px] border-white bg-slate-100">
                <img src="{{ asset('asset/images/image-30.png') }}" alt="Ilustrasi Sistem" class="w-full h-full object-cover">
                <!-- Efek gradasi halus di atas gambar -->
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/20 to-transparent"></div>
            </div>
        </div>

    </div>

    <!-- Fitur Bawah (Card Grid) -->
    <div class="bg-white py-20 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Card 1 -->
            <div class="p-8 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-300 group">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <!-- SVG dengan kunci ukuran (pengaman) -->
                    <svg style="width: 28px; height: 28px;" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Pantauan Real-Time</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Akses video langsung tanpa jeda dari seluruh titik kamera, memastikan respons cepat terhadap setiap kejadian.</p>
            </div>

            <!-- Card 2 -->
            <div class="p-8 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-300 group">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <!-- SVG dengan kunci ukuran (pengaman) -->
                    <svg style="width: 28px; height: 28px;" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Cloud Storage</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Rekaman tersimpan secara aman di server awan. Bebas dari risiko kehilangan data perangkat keras lokal.</p>
            </div>

            <!-- Card 3 -->
            <div class="p-8 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-300 group">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <!-- SVG dengan kunci ukuran (pengaman) -->
                    <svg style="width: 28px; height: 28px;" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Akses Multi-Platform</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Kendali penuh teroptimasi untuk pemantauan via komputer pusat maupun smartphone saat Anda bepergian.</p>
            </div>

        </div>
    </div>

</body>
</html>