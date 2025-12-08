@extends('adversting.layouts.app')

@section('content')


{{-- =====================================================
    DESKTOP VERSION (md ke atas)
===================================================== --}}
<div class="hidden md:block">

    {{-- ================= HERO DESKTOP ================= --}}
    <section class="pt-40 pb-28 bg-white fade-soft">
        <div class="max-w-6xl mx-auto px-6 text-center">

            <h1 class="text-4xl md:text-5xl font-extrabold text-[#0f0f0f] leading-tight">
                <span class="hero-dark">Layanan</span>
                <span class="hero-blue">Percetakan</span>
                <span class="hero-yellow">Modern</span>
                <span class="hero-dark">&</span>
                <span class="hero-red">Profesional</span>
            </h1>

            <p class="text-gray-600 text-lg mt-4 max-w-2xl mx-auto">
                Solusi percetakan lengkap dengan teknologi modern, konsistensi warna,
                dan estetika premium untuk brand masa kini.
            </p>

            <div class="w-28 h-[3px] mx-auto mt-8 rounded-full"
                style="background: var(--brand-blue);"></div>

        </div>
    </section>



    {{-- ================= KATEGORI UTAMA DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa] fade-soft">
        <div class="max-w-7xl mx-auto px-6">

            <h2 class="section-title">Kategori Utama Layanan</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-14 rounded-full"
                style="background: var(--brand-yellow);"></div>

            <div class="grid md:grid-cols-3 gap-14">

                <div class="text-center group">
                    <div class="w-20 h-20 rounded-2xl mx-auto flex items-center justify-center shadow-md bg-white
                                group-hover:-translate-y-1 transition">
                        <img src="{{ asset('images/icons/indoor.png') }}" class="w-10 opacity-80">
                    </div>
                    <h3 class="font-semibold text-xl mt-6" style="color: var(--brand-blue);">Indoor Printing</h3>
                    <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                        Poster, signage indoor, booth display, booklet & brosur.
                    </p>
                </div>

                <div class="text-center group">
                    <div class="w-20 h-20 rounded-2xl mx-auto flex items-center justify-center shadow-md bg-white
                                group-hover:-translate-y-1 transition">
                        <img src="{{ asset('images/icons/outdoor.png') }}" class="w-10 opacity-80">
                    </div>
                    <h3 class="font-semibold text-xl mt-6" style="color: var(--brand-red);">Outdoor Printing</h3>
                    <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                        Baliho, billboard, branding kendaraan, neonbox, banner tahan cuaca.
                    </p>
                </div>

                <div class="text-center group">
                    <div class="w-20 h-20 rounded-2xl mx-auto flex items-center justify-center shadow-md bg-white
                                group-hover:-translate-y-1 transition">
                        <img src="{{ asset('images/icons/multi.png') }}" class="w-10 opacity-80">
                    </div>
                    <h3 class="font-semibold text-xl mt-6" style="color: var(--brand-yellow);">Multi Product</h3>
                    <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                        Stiker, label produk, packaging branding, kartu nama & merchandise.
                    </p>
                </div>

            </div>

        </div>
    </section>



    {{-- ================= WHY WORK WITH US DESKTOP ================= --}}
    <section class="py-24 bg-white fade-soft">
        <div class="max-w-6xl mx-auto px-6">

            <h2 class="section-title">Mengapa Memilih Kami?</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-14 rounded-full"
                style="background: var(--brand-blue);"></div>

            <div class="grid md:grid-cols-4 gap-10 text-center">

                <div>
                    <h3 class="font-bold text-lg text-[#0f0f0f]">Presisi Warna</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Teknologi kalibrasi memastikan warna selalu konsisten.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold text-lg text-[#0f0f0f]">Produksi Cepat</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Workflow digital efisien tanpa kompromi kualitas.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold text-lg text-[#0f0f0f]">Material Premium</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Vinyl, PVC, UV print, art paper, premium sticker.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold text-lg text-[#0f0f0f]">Kustomisasi Fleksibel</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Semua produk bisa mengikuti kebutuhan brand Anda.
                    </p>
                </div>

            </div>

        </div>
    </section>



    {{-- ================= WORKFLOW DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa] fade-soft">
        <div class="max-w-6xl mx-auto px-6">

            <h2 class="section-title">Alur Kerja Profesional</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-14 rounded-full"
                style="background: var(--brand-yellow);"></div>

            <div class="grid md:grid-cols-4 gap-10 text-center">

                <div class="p-6 bg-white rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-[#0f0f0f]">01</div>
                    <h3 class="font-semibold mt-2">Konsultasi</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Diskusikan kebutuhan Anda.
                    </p>
                </div>

                <div class="p-6 bg-white rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-[#0f0f0f]">02</div>
                    <h3 class="font-semibold mt-2">Desain</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        File disiapkan hingga siap cetak.
                    </p>
                </div>

                <div class="p-6 bg-white rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-[#0f0f0f]">03</div>
                    <h3 class="font-semibold mt-2">Produksi</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Proses print menggunakan mesin modern.
                    </p>
                </div>

                <div class="p-6 bg-white rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-[#0f0f0f]">04</div>
                    <h3 class="font-semibold mt-2">Finishing</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Laminasi, cutting, QC & pengemasan.
                    </p>
                </div>

            </div>

        </div>
    </section>



    {{-- ================= GALLERY DESKTOP ================= --}}
    <section class="py-24 bg-white fade-soft">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="section-title">Hasil Karya Kami</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-14 rounded-full"
                style="background: var(--brand-blue);"></div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach(['gallery1.jpg','gallery2.jpg','gallery3.jpg','gallery4.jpg','gallery5.jpg','gallery6.jpg'] as $img)
                    <div class="rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                        <img src="{{ asset('images/' . $img) }}"
                            class="w-full h-56 object-cover">
                    </div>
                @endforeach
            </div>

        </div>
    </section>



    {{-- ================= CLIENTS DESKTOP ================= --}}
    <section class="py-24 bg-white fade-soft">
        <div class="max-w-6xl mx-auto px-6 text-center">

            <h2 class="section-title">Dipercaya Banyak Brand</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-14 rounded-full"
                style="background: var(--brand-blue);"></div>

            <p class="text-gray-600 max-w-xl mx-auto mb-12">
                Kami telah mendukung banyak perusahaan, brand nasional, event organizer hingga UMKM.
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                @foreach(['logo1.png','logo2.png','logo3.png','logo4.png'] as $logo)
                    <div class="opacity-60 hover:opacity-100 transition">
                        <img src="{{ asset('images/clients/'.$logo) }}"
                            class="mx-auto h-12 object-contain">
                    </div>
                @endforeach
            </div>

        </div>
    </section>



    {{-- ================= EQUIPMENT DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa] fade-soft">
        <div class="max-w-7xl mx-auto px-6">

            <h2 class="section-title text-center">Teknologi & Peralatan Produksi</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-14 rounded-full"
                style="background: var(--brand-yellow);"></div>

            <p class="text-gray-600 text-center max-w-3xl mx-auto mb-14">
                Untuk menjaga kualitas terbaik, kami menggunakan perangkat modern yang telah terkalibrasi.
            </p>

            <div class="grid md:grid-cols-3 gap-10">

                <div class="bg-white rounded-2xl shadow p-6">
                    <img src="{{ asset('images/equip/print1.jpg') }}"
                        class="w-full h-48 object-cover rounded-xl mb-4">
                    <h3 class="font-semibold text-lg">Large Format Printer</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Resolusi tinggi untuk billboard & media outdoor.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow p-6">
                    <img src="{{ asset('images/equip/print2.jpg') }}"
                        class="w-full h-48 object-cover rounded-xl mb-4">
                    <h3 class="font-semibold text-lg">Indoor UV Printer</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Hasil tajam & warna konsisten.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow p-6">
                    <img src="{{ asset('images/equip/print3.jpg') }}"
                        class="w-full h-48 object-cover rounded-xl mb-4">
                    <h3 class="font-semibold text-lg">Finishing & Cutting</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        Mesin finishing otomatis untuk hasil rapi & presisi.
                    </p>
                </div>

            </div>

        </div>
    </section>



    {{-- ================= FAQ DESKTOP ================= --}}
    <section class="py-24 bg-white fade-soft">
        <div class="max-w-5xl mx-auto px-6">

            <h2 class="section-title text-center">Pertanyaan yang Sering Diajukan</h2>
            <div class="w-20 h-[3px] mx-auto mt-3 mb-12 rounded-full"
                style="background: var(--brand-blue);"></div>

            <div class="space-y-4">

                <details class="group bg-[#fafafa] p-5 rounded-xl border border-gray-200">
                    <summary class="flex justify-between items-center cursor-pointer font-medium">
                        Berapa lama proses produksi?
                        <span class="transition-transform group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="text-gray-600 text-sm mt-3">
                        Estimasi 1–3 hari, beberapa produk bisa 24 jam.
                    </p>
                </details>

                <details class="group bg-[#fafafa] p-5 rounded-xl border border-gray-200">
                    <summary class="flex justify-between items-center cursor-pointer font-medium">
                        Apakah bisa request desain?
                        <span class="transition-transform group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="text-gray-600 text-sm mt-3">
                        Bisa, tim desain kami siap membantu.
                    </p>
                </details>

                <details class="group bg-[#fafafa] p-5 rounded-xl border border-gray-200">
                    <summary class="flex justify-between items-center cursor-pointer font-medium">
                        Apakah menyediakan pemasangan outdoor?
                        <span class="transition-transform group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="text-gray-600 text-sm mt-3">
                        Ya, pemasangan billboard, baliho, signage & branding kendaraan.
                    </p>
                </details>

            </div>

        </div>
    </section>



    {{-- ================= CTA DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa] text-center fade-soft">

        <h2 class="text-3xl font-bold text-[#0f0f0f]">
            Siap Tingkatkan Branding Bisnis Anda?
        </h2>

        <div class="w-20 h-[3px] mx-auto mt-3 mb-4 rounded-full"
            style="background: var(--brand-yellow);"></div>

        <p class="text-gray-600 max-w-xl mx-auto">
            Dapatkan hasil cetak premium & presisi untuk mendukung identitas visual brand Anda.
        </p>

        <a href="{{ route('contact') }}" class="btn-primary mt-8 inline-block">
            Hubungi Kami
        </a>

    </section>

</div>







{{-- =====================================================
    MOBILE VERSION (0–md)
===================================================== --}}
<div class="block md:hidden">

    {{-- ================= HERO MOBILE ================= --}}
    <section class="pt-28 pb-14 text-center px-6 fade-soft">
        <h1 class="text-[2rem] font-extrabold leading-tight text-[#0f0f0f]">
            Layanan Profesional & Modern
        </h1>

        <p class="text-gray-600 text-base mt-3">
            Solusi percetakan lengkap untuk kebutuhan brand.
        </p>

        <div class="w-20 h-[3px] mx-auto mt-5 rounded-full"
            style="background: var(--brand-blue);"></div>
    </section>



    {{-- ================= KATEGORI UTAMA MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft space-y-10">

        <div class="text-center">
            <img src="{{ asset('images/icons/indoor.png') }}" class="w-12 mx-auto">
            <h3 class="font-semibold mt-3">Indoor Printing</h3>
            <p class="text-sm text-gray-600 mt-1">
                Poster, brosur, signage ruangan & display.
            </p>
        </div>

        <div class="text-center">
            <img src="{{ asset('images/icons/outdoor.png') }}" class="w-12 mx-auto">
            <h3 class="font-semibold mt-3">Outdoor Printing</h3>
            <p class="text-sm text-gray-600 mt-1">
                Baliho, kendaraan, neonbox, banner outdoor.
            </p>
        </div>

        <div class="text-center">
            <img src="{{ asset('images/icons/multi.png') }}" class="w-12 mx-auto">
            <h3 class="font-semibold mt-3">Multi Product</h3>
            <p class="text-sm text-gray-600 mt-1">
                Stiker, packaging, kartu nama & merchandise.
            </p>
        </div>

    </section>



    {{-- ================= WORKFLOW MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft">

        <h2 class="text-xl font-bold text-center">Alur Kerja</h2>

        <div class="w-16 h-[3px] mx-auto mt-2 mb-8 rounded-full"
            style="background: var(--brand-yellow);"></div>

        <div class="space-y-6 text-sm text-gray-700">

            <div>
                <p class="font-semibold text-[#0f0f0f]">01 — Konsultasi</p>
                <p>Diskusi kebutuhan Anda.</p>
            </div>

            <div>
                <p class="font-semibold text-[#0f0f0f]">02 — Desain</p>
                <p>File disiapkan hingga siap cetak.</p>
            </div>

            <div>
                <p class="font-semibold text-[#0f0f0f]">03 — Produksi</p>
                <p>Proses cetak dengan mesin modern.</p>
            </div>

            <div>
                <p class="font-semibold text-[#0f0f0f]">04 — Finishing</p>
                <p>Pemotongan, laminasi & QC.</p>
            </div>

        </div>

    </section>



    {{-- ================= GALLERY MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft">

        <h2 class="text-xl font-bold text-center">Galeri</h2>

        <div class="grid grid-cols-2 gap-3 mt-6">
            @foreach(['gallery1.jpg','gallery2.jpg','gallery3.jpg','gallery4.jpg'] as $img)
                <img src="{{ asset('images/' . $img) }}"
                    class="w-full h-32 rounded-xl object-cover shadow">
            @endforeach
        </div>

    </section>



    {{-- ================= CTA MOBILE ================= --}}
    <section class="py-14 px-6 text-center fade-soft">
        <h2 class="text-xl font-bold text-[#0f0f0f]">Mulai Cetak Sekarang?</h2>

        <p class="text-gray-600 text-sm mt-2 max-w-xs mx-auto">
            Konsultasikan kebutuhan printing Anda dan dapatkan hasil terbaik.
        </p>

        <a href="{{ route('contact') }}" class="btn-primary mt-6 inline-block px-10 py-3 text-sm">
            Hubungi Kami
        </a>
    </section>

</div>



@endsection
