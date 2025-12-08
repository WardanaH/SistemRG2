<div class="hidden md:grid max-w-7xl mx-auto px-6 grid-cols-5 gap-10">

    <!-- LOGO -->
    <div>
        <img src="{{ asset('images/Logo Restu Guru Promosindo.webp') }}" class="h-16 mb-4" alt="Logo">
        <h3 class="text-xl font-semibold text-gray-900">CV. Restu Guru Promosindo</h3>

        <p class="text-gray-500 text-sm mt-2 leading-relaxed max-w-xs">
            Percetakan modern berbasis teknologi dengan fokus pada kualitas,
            presisi, dan pelayanan profesional.
        </p>

        <div class="mt-5">
            <h4 class="font-semibold text-gray-900 mb-1 text-sm">Jam Operasional</h4>
            <p class="text-gray-500 text-sm leading-relaxed">
                Senin – Sabtu: 08.00 – 17.00 <br>
                Minggu & Hari Besar: Tutup
            </p>
        </div>
    </div>

    <!-- NAVIGASI -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-4">Navigasi</h4>
        <ul class="space-y-2 text-sm text-gray-600">
            <li><a class="footer-link" href="{{ route('home') }}">Home</a></li>
            <li><a class="footer-link" href="{{ route('about') }}">Tentang</a></li>
            <li><a class="footer-link" href="{{ route('services') }}">Layanan</a></li>
            <li><a class="footer-link" href="{{ route('contact') }}">Kontak</a></li>
        </ul>
    </div>

    <!-- LAYANAN -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-4">Layanan</h4>
        <ul class="space-y-2 text-sm text-gray-600">
            <li><a class="footer-link" href="{{ route('services') }}">Indoor Printing</a></li>
            <li><a class="footer-link" href="{{ route('services') }}">Outdoor Printing</a></li>
            <li><a class="footer-link" href="{{ route('services') }}">Merch & Multi Product</a></li>
        </ul>
    </div>

    <!-- IKUTI KAMI -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-4">Ikuti Kami</h4>
        <ul class="space-y-3 text-sm text-gray-700">

            <li class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                    <path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2V9.6c0-2
                    1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3
                    1.2V12h2.2l-.4 3h-1.8v7A10 10 0 0 0 22 12"/>
                </svg>
                Facebook
            </li>

            <li class="flex items-center gap-3">
                <svg width="22" height="22" viewBox="0 0 512 512">
                    <defs>
                        <linearGradient id="IGfix" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#f58529"/>
                            <stop offset="30%" stop-color="#dd2a7b"/>
                            <stop offset="60%" stop-color="#8134af"/>
                            <stop offset="100%" stop-color="#515bd4"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#IGfix)" d="M349.33 69.33H162.67C111.2 69.33 69.33
                    111.2 69.33 162.67v186.67c0 51.47 41.87 93.33
                    93.33 93.33h186.67c51.47 0 93.33-41.87
                    93.33-93.33V162.67c0-51.47-41.87-93.34-93.33-93.34z
                    M256 346.67A90.67 90.67 0 1 1 346.67 256
                    90.8 90.8 0 0 1 256 346.67zm93.87-194.54a21.33
                    21.33 0 1 1 21.33-21.33 21.33 21.33 0 0 1-21.33
                    21.33z"/>
                </svg>
                Instagram
            </li>

            <li class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="#FF0000" viewBox="0 0 24 24">
                    <path d="M21.6 7.2s-.2-1.5-.8-2.2c-.8-.9-1.7-.9-2.1-1C15.9
                    4 12 4 12 4s-3.9 0-6.7.3c-.4
                    0-1.3.1-2.1 1-.6.7-.8 2.2-.8 2.2S2
                    8.9 2 10.6v1.7C2 14 2.2 15.7 2.2
                    15.7s.2 1.5.8 2.2c.8.9 1.9.9
                    2.4 1C7.2 19.2 12 19.3 12 19.3s3.9
                    0 6.7-.3c.4 0 1.3-.1 2.1-1 .6-.7.8-2.2.8-2.2s.2-1.7.2-3.4v-1.7c0-1.7-.2-3.4-.2-3.4z
                    M10 14.7V8.9l5.4 2.9L10 14.7z"/>
                </svg>
                YouTube
            </li>

        </ul>
    </div>

    <!-- LOKASI -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-4">Lokasi Kantor</h4>

        <ul class="space-y-3 text-sm text-gray-600">
            @foreach(['Banjarbaru','Banjar','Banjarmasin','Martapura','Liang Anggang','Pelaihari'] as $loc)
            <li class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="#E62129" viewBox="0 0 24 24">
                    <path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7
                    13s7-7.8 7-13a7 7 0 0 0-7-7zm0
                    9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5
                    0 0 1 12 11.5z"/>
                </svg>
                {{ $loc }}
            </li>
            @endforeach
        </ul>
    </div>

</div>


    {{-- COPYRIGHT --}}
    <div class="text-center mt-16 pt-6 border-t border-gray-200">
        <p class="text-gray-400 text-xs">
            © {{ date('Y') }} Restu Guru Promosindo — All rights reserved.
        </p>
    </div>

</footer>
