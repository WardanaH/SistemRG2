@extends('adversting.layouts.app')

@section('content')


{{-- =====================================================
    ABOUT — DESKTOP VERSION (md ke atas)
===================================================== --}}
<div class="hidden md:block">

    {{-- ================= HERO DESKTOP ================= --}}
    <section class="pt-36 pb-20 bg-white text-center fade-soft">
        <div class="max-w-3xl mx-auto px-6">

            <h1 class="hero-title leading-tight font-extrabold text-[2.8rem] md:text-[3.6rem]">
                <span class="hero-dark">Tentang</span>
                <span class="hero-red">Restu</span>
                <span class="hero-blue">Guru</span>
                <span class="hero-yellow">Promosindo</span>
            </h1>

            <p class="mt-5 text-gray-600 text-lg leading-relaxed max-w-2xl mx-auto">
                Solusi percetakan modern yang menggabungkan teknologi, estetika, dan konsistensi
                untuk menghadirkan hasil terbaik bagi bisnis Anda.
            </p>

            <div class="w-28 h-1 mx-auto mt-6 rounded-full"
                style="background: linear-gradient(90deg, var(--brand-blue), var(--brand-red), var(--brand-yellow));"></div>
        </div>
    </section>



    {{-- ================= COMPANY STORY DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa]">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-14 items-center">

            <div class="fade-soft">
                <h2 class="text-3xl font-bold text-[#0f0f0f] mb-3 border-l-4 pl-3"
                    style="border-color: var(--brand-red);">Siapa Kami?</h2>

                <p class="text-gray-600 leading-relaxed mb-3">
                    CV. Restu Guru Promosindo adalah perusahaan percetakan yang berfokus pada
                    kualitas, presisi, dan pelayanan profesional.
                </p>

                <p class="text-gray-600 leading-relaxed">
                    Dengan pengalaman bertahun-tahun di industri percetakan modern, kami memahami
                    bahwa setiap detail visual adalah representasi penting dari identitas brand Anda.
                </p>
            </div>

            <div class="fade-soft soft-float">
                <img src="{{ asset('images/about-office.jpg') }}"
                    class="rounded-2xl shadow-xl object-cover w-full h-80 hover:scale-[1.015] transition duration-300"
                    alt="Office">
            </div>

        </div>
    </section>



    {{-- ================= OWNER PROFILE DESKTOP ================= --}}
    <section class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">

            <div class="fade-soft">
                <img src="{{ asset('images/owner.jpg') }}"
                    class="rounded-2xl shadow-xl w-full h-[450px] object-cover soft-float"
                    alt="Owner">
            </div>

            <div class="fade-soft">
                <h2 class="text-3xl font-bold text-[#0f0f0f] mb-4">
                    <span style="border-bottom: 4px solid var(--brand-blue); padding-bottom: 4px;">
                        Owner
                    </span>
                </h2>

                <h3 class="text-2xl font-semibold text-[#0f0f0f] leading-snug">
                    Putra Qomaluddin Attar Nurriqli, M.I.Kom
                </h3>

                <p class="text-gray-600 leading-relaxed mt-4">
                    Putra Qomaluddin Attar Nurriqli adalah pendiri sekaligus Direktur CV. Restu Guru Promosindo.
                </p>

                <p class="text-gray-600 leading-relaxed mt-3">
                    Dengan pengalaman komunikasi yang matang, beliau membangun Restu Guru Promosindo sebagai
                    percetakan modern dengan fokus kualitas dan presisi.
                </p>

                <p class="text-gray-600 leading-relaxed mt-3">
                    Di bawah kepemimpinannya, perusahaan berkembang pesat dengan hasil cetak yang konsisten & rapi.
                </p>
            </div>

        </div>
    </section>



    {{-- ================= CORE VALUES DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa]">
        <div class="max-w-6xl mx-auto px-6 text-center">

            <h2 class="section-title fade-soft">Nilai Utama Kami</h2>
            <p class="section-desc fade-soft">Prinsip kerja yang kami pegang teguh.</p>

            <div class="grid md:grid-cols-3 gap-12 mt-14">

                <div class="fade-soft hover:-translate-y-1 transition">
                    <h3 class="text-xl font-semibold text-[#0f0f0f]">Quality First</h3>
                    <div class="w-10 h-1 mx-auto mt-2 rounded-full" style="background: var(--brand-red);"></div>
                    <p class="text-gray-600 mt-3">Kualitas adalah prioritas utama.</p>
                </div>

                <div class="fade-soft hover:-translate-y-1 transition">
                    <h3 class="text-xl font-semibold text-[#0f0f0f]">Precision</h3>
                    <div class="w-10 h-1 mx-auto mt-2 rounded-full" style="background: var(--brand-blue);"></div>
                    <p class="text-gray-600 mt-3">Presisi warna & detail profesional.</p>
                </div>

                <div class="fade-soft hover:-translate-y-1 transition">
                    <h3 class="text-xl font-semibold text-[#0f0f0f]">Reliability</h3>
                    <div class="w-10 h-1 mx-auto mt-2 rounded-full" style="background: var(--brand-yellow);"></div>
                    <p class="text-gray-600 mt-3">Tepat waktu & konsisten.</p>
                </div>

            </div>

        </div>
    </section>



    {{-- ================= TIMELINE DESKTOP ================= --}}
    <section class="py-24 bg-white fade-soft">
        <div class="max-w-5xl mx-auto px-6">

            <h2 class="section-title fade-soft">Sejarah Perusahaan</h2>
            <p class="section-desc fade-soft">Perjalanan Restu Guru Promosindo.</p>

            <div class="relative border-l-4 mt-14 space-y-12"
                 style="border-color: var(--brand-blue);">

                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-900">2015 — Berdiri</h3>
                    <p class="text-gray-600 mt-2">Didirikan sebagai percetakan lokal.</p>
                </div>

                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-900">2018 — Ekspansi</h3>
                    <p class="text-gray-600 mt-2">Penambahan layanan indoor & outdoor.</p>
                </div>

                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-900">2021 — Modernisasi</h3>
                    <p class="text-gray-600 mt-2">Mengadopsi digital workflow modern.</p>
                </div>

                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-900">2024 — Regional</h3>
                    <p class="text-gray-600 mt-2">Ekspansi ke Banjarbaru, Banjarmasin, Martapura, dll.</p>
                </div>

            </div>

        </div>
    </section>



    {{-- ================= TEAM DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa] fade-soft">
        <div class="max-w-6xl mx-auto px-6 text-center">

            <h2 class="section-title fade-soft">Meet Our Team</h2>
            <p class="section-desc fade-soft">Tim profesional & berpengalaman.</p>

            <div class="grid md:grid-cols-3 gap-12 mt-14">

                <div class="fade-soft hover:-translate-y-1 transition">
                    <img src="{{ asset('images/owner.jpg') }}"
                        class="w-36 h-36 mx-auto rounded-full object-cover shadow-md mb-4">
                    <h3 class="font-semibold text-lg text-gray-900">Putra Qomaluddin Attar N.</h3>
                    <p class="text-gray-500 text-sm">Direktur Utama</p>
                </div>

                <div class="fade-soft hover:-translate-y-1 transition">
                    <img src="{{ asset('images/team2.jpg') }}"
                        class="w-36 h-36 mx-auto rounded-full object-cover shadow-md mb-4">
                    <h3 class="font-semibold text-lg text-gray-900">Nama Staf 1</h3>
                    <p class="text-gray-500 text-sm">Head of Production</p>
                </div>

                <div class="fade-soft hover:-translate-y-1 transition">
                    <img src="{{ asset('images/team3.jpg') }}"
                        class="w-36 h-36 mx-auto rounded-full object-cover shadow-md mb-4">
                    <h3 class="font-semibold text-lg text-gray-900">Nama Staf 2</h3>
                    <p class="text-gray-500 text-sm">Client Relation</p>
                </div>

            </div>

        </div>
    </section>


    {{-- ================= CTA DESKTOP ================= --}}
    <section class="py-20 bg-white text-center fade-soft">
        <h2 class="text-3xl font-bold text-[#0f0f0f]">Siap Bekerja Dengan Kami?</h2>
        <p class="text-gray-600 max-w-lg mx-auto mt-3">
            Kami siap mendukung kebutuhan percetakan Anda dengan hasil terbaik & presisi tinggi.
        </p>
        <a href="{{ route('contact') }}" class="btn-primary mt-8 inline-block">Hubungi Kami</a>
    </section>

</div>









{{-- =====================================================
    ABOUT — MOBILE VERSION (0–md)
===================================================== --}}
<div class="block md:hidden">

    {{-- ================= HERO MOBILE ================= --}}
    <section class="pt-28 pb-14 text-center px-6 fade-soft">
        <h1 class="text-[2.1rem] font-extrabold leading-tight">
            <span class="hero-dark">Tentang</span>
            <span class="hero-red">Restu</span>
            <span class="hero-blue">Guru</span>
        </h1>

        <p class="mt-4 text-gray-600 text-base leading-relaxed">
            Percetakan modern dengan fokus pada presisi dan kualitas premium.
        </p>

        <div class="w-24 h-[3px] mx-auto mt-5 rounded-full"
             style="background: var(--brand-blue);"></div>
    </section>


    {{-- ================= COMPANY STORY MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft">
        <img src="{{ asset('images/about-office.jpg') }}"
             class="w-full h-52 object-cover rounded-xl shadow mb-5">

        <h2 class="text-xl font-bold text-[#0f0f0f] mb-2">Siapa Kami?</h2>

        <p class="text-gray-600 text-sm leading-relaxed">
            CV. Restu Guru Promosindo adalah perusahaan percetakan yang berfokus pada kualitas dan presisi.
        </p>
    </section>



    {{-- ================= OWNER MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft">
        <img src="{{ asset('images/owner.jpg') }}"
             class="w-full h-64 object-cover rounded-xl shadow mb-4">

        <h2 class="text-xl font-bold">Owner</h2>
        <p class="font-semibold mt-1">Putra Qomaluddin Attar Nurriqli, M.I.Kom</p>

        <p class="text-gray-600 text-sm mt-3 leading-relaxed">
            Pendiri sekaligus Direktur CV. Restu Guru Promosindo dengan pengalaman komunikasi & branding modern.
        </p>
    </section>



    {{-- ================= VALUES MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft text-center">
        <h2 class="text-xl font-bold">Nilai Utama</h2>

        <div class="w-16 h-[3px] mx-auto mt-2 mb-8 rounded-full"
            style="background: var(--brand-yellow);"></div>

        <div class="space-y-8">

            <div>
                <h3 class="font-semibold">Quality First</h3>
                <p class="text-sm text-gray-600">Kualitas adalah prioritas utama.</p>
            </div>

            <div>
                <h3 class="font-semibold">Precision</h3>
                <p class="text-sm text-gray-600">Presisi warna & detail tinggi.</p>
            </div>

            <div>
                <h3 class="font-semibold">Reliability</h3>
                <p class="text-sm text-gray-600">Tepat waktu & konsisten.</p>
            </div>

        </div>
    </section>



    {{-- ================= TIMELINE MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft">
        <h2 class="text-xl font-bold text-center">Sejarah</h2>

        <div class="mt-6 space-y-5 text-sm text-gray-700">
            <p><strong>2015</strong> — Berdiri sebagai percetakan lokal.</p>
            <p><strong>2018</strong> — Ekspansi indoor & outdoor.</p>
            <p><strong>2021</strong> — Modernisasi workflow.</p>
            <p><strong>2024</strong> — Ekspansi regional.</p>
        </div>
    </section>



    {{-- ================= CTA MOBILE ================= --}}
    <section class="py-14 px-6 text-center fade-soft">
        <h2 class="text-xl font-bold text-[#0f0f0f]">Siap Bekerja Dengan Kami?</h2>

        <p class="text-gray-600 text-sm mt-2 max-w-xs mx-auto">
            Kami siap mendukung kebutuhan percetakan Anda dengan pelayanan profesional.
        </p>

        <a href="{{ route('contact') }}" class="btn-primary mt-6 inline-block px-10 py-3 text-sm">
            Hubungi Kami
        </a>
    </section>

</div>

@endsection
