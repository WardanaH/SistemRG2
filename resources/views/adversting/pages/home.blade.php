@extends('adversting.layouts.app')

@section('content')

{{-- =====================================================
     HOME — DESKTOP VERSION (md ke atas)
===================================================== --}}
<div class="hidden md:block">

    {{-- ================= HERO DESKTOP ================= --}}
    <section class="fade-soft" style="padding: 140px 8% 120px;">
        <div class="max-w-3xl mx-auto text-center">

            <h1 class="hero-title text-[3rem] md:text-[3.4rem] font-extrabold leading-tight">
                <span class="hero-red">Restu</span>
                <span class="hero-yellow">Guru</span>
                <span class="hero-blue">Promosindo</span><br>
                <span class="hero-dark">Digital</span>
                <span class="hero-dark">Printing</span>
                <span class="hero-dark">untuk</span>
                <span class="hero-dark">Brand</span>
                <span class="hero-dark">Modern</span>
            </h1>

            <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Solusi percetakan profesional dengan presisi tinggi, warna konsisten,
                dan estetika modern untuk kebutuhan brand masa kini.
            </p>

            <div class="w-40 h-1 mx-auto mt-6 rounded-full"
                style="background: var(--rg-gradient);"></div>

            <a href="{{ route('services') }}" class="btn-primary mt-10 inline-block">
                View Services
            </a>
        </div>
    </section>

    {{-- ================= WHY CHOOSE US DESKTOP ================= --}}
    <section class="section-box fade-soft">
        <h2 class="section-title">Why Choose Us</h2>

        <div class="w-24 h-[3px] mx-auto mt-3 mb-8 rounded-full"
            style="background: var(--brand-blue);"></div>

        <p class="section-desc">Keunggulan utama yang membuat layanan kami dipercaya banyak brand besar.</p>

        <div class="value-grid">

            <div class="value-item hover:shadow-lg transition">
                <img src="{{ asset('images/Why Choose Us 1.jpg') }}" class="value-img" style="height: 160px;">
                <h3 class="text-lg font-semibold mt-3">High Precision</h3>
                <p class="text-gray-600 text-sm mt-1">Kualitas cetak detail & warna akurat.</p>
            </div>

            <div class="value-item hover:shadow-lg transition">
                <img src="{{ asset('images/Why Choose Us 2.jpg') }}" class="value-img" style="height: 160px;">
                <h3 class="text-lg font-semibold mt-3">Consistent Color</h3>
                <p class="text-gray-600 text-sm mt-1">Stabil dari batch pertama sampai terakhir.</p>
            </div>

            <div class="value-item hover:shadow-lg transition">
                <img src="{{ asset('images/Why Choose Us 3.jpg') }}" class="value-img" style="height: 160px;">
                <h3 class="text-lg font-semibold mt-3">On-Time Delivery</h3>
                <p class="text-gray-600 text-sm mt-1">Tepat waktu & proses produksi efisien.</p>
            </div>
        </div>
    </section>

    {{-- ================= SERVICES DESKTOP ================= --}}
    <section class="section-box fade-soft">
        <h2 class="section-title">Our Services</h2>

        <div class="w-24 h-[3px] mx-auto mt-3 mb-8 rounded-full"
            style="background: var(--brand-red);"></div>

        <p class="section-desc">Layanan percetakan profesional untuk mendukung kebutuhan visual bisnis modern.</p>

        {{-- Service 1 --}}
        <div class="service-row">
            <div class="service-text">
                <h3 style="color: var(--brand-blue);">Indoor Printing</h3>
                <p>Poster, backdrop, banner, roll-up — diproduksi dengan kualitas tinggi & warna presisi.</p>
                <a href="{{ route('services') }}" class="link-accent">Learn More →</a>
            </div>

            <div class="service-image">
                <img src="{{ asset('images/Our Services 1.jpg') }}"
                    class="rounded-2xl"
                    style="height: 260px; box-shadow: none;">
            </div>
        </div>

        {{-- Service 2 --}}
        <div class="service-row reverse">
            <div class="service-text">
                <h3 style="color: var(--brand-red);">Outdoor Printing</h3>
                <p>Billboard, signage, branding luar ruang dengan material tahan cuaca.</p>
                <a href="{{ route('services') }}" class="link-accent">Learn More →</a>
            </div>

            <div class="service-image">
                <img src="{{ asset('images/Our Services 2.jpg') }}"
                    class="rounded-2xl"
                    style="height: 260px;">
            </div>
        </div>

        {{-- Service 3 --}}
        <div class="service-row">
            <div class="service-text">
                <h3 style="color: var(--brand-yellow);">Merch & Multi Product</h3>
                <p>Stiker, packaging, merchandise, dan berbagai produk custom lainnya.</p>
                <a href="{{ route('services') }}" class="link-accent">Learn More →</a>
            </div>

            <div class="service-image">
                <img src="{{ asset('images/Our Services 3.jpg') }}"
                    class="rounded-2xl"
                    style="height: 260px;">
            </div>
        </div>
    </section>

    {{-- ================= CTA DESKTOP ================= --}}
    <section class="cta-section fade-soft">
        <h2>Ready to Print with Us?</h2>

        <div class="w-24 h-[3px] mx-auto mt-3 mb-6 rounded-full"
            style="background: var(--brand-yellow);"></div>

        <p class="cta-desc">Dapatkan kualitas cetak premium yang meningkatkan citra brand Anda.</p>

        <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
    </section>
</div>





{{-- =====================================================
     HOME — MOBILE VERSION (0–md)
===================================================== --}}
<div class="block md:hidden">

    {{-- ================= HERO MOBILE ================= --}}
    <section class="pt-28 pb-16 px-6 text-center fade-soft">
        <h1 class="text-[2.2rem] font-extrabold leading-tight">
            <span class="hero-red">Restu</span>
            <span class="hero-yellow">Guru</span>
            <span class="hero-blue">Promosindo</span>
        </h1>

        <h2 class="mt-1 text-xl font-semibold text-gray-800">
            Digital Printing untuk Brand Modern
        </h2>

        <p class="mt-4 text-gray-600 text-base leading-relaxed">
            Solusi percetakan presisi tinggi dan warna konsisten untuk kebutuhan brand masa kini.
        </p>

        <div class="w-32 h-1 mx-auto mt-5 rounded-full"
            style="background: var(--rg-gradient);"></div>

        <a href="{{ route('services') }}" class="btn-primary mt-8 inline-block text-sm px-8 py-3">
            View Services
        </a>
    </section>



    {{-- ================= WHY CHOOSE US MOBILE ================= --}}
    <section class="py-14 px-6 fade-soft">
        <h2 class="text-2xl font-bold text-center text-[#0f0f0f]">Why Choose Us</h2>

        <div class="w-20 h-[3px] mx-auto mt-3 mb-6 rounded-full"
            style="background: var(--brand-blue);"></div>

        <div class="space-y-8">

            <div class="text-center">
                <img src="{{ asset('images/service-indoor.jpg') }}"
                    class="w-full h-40 rounded-xl object-cover shadow">
                <h3 class="mt-3 font-semibold">High Precision</h3>
                <p class="text-sm text-gray-600">Kualitas cetak detail & akurasi warna tinggi.</p>
            </div>

            <div class="text-center">
                <img src="{{ asset('images/service-outdoor.jpg') }}"
                    class="w-full h-40 rounded-xl object-cover shadow">
                <h3 class="mt-3 font-semibold">Consistent Color</h3>
                <p class="text-sm text-gray-600">Warna stabil dari awal hingga akhir produksi.</p>
            </div>

            <div class="text-center">
                <img src="{{ asset('images/service-multi.jpg') }}"
                    class="w-full h-40 rounded-xl object-cover shadow">
                <h3 class="mt-3 font-semibold">On-Time Delivery</h3>
                <p class="text-sm text-gray-600">Pengerjaan cepat & tepat waktu.</p>
            </div>

        </div>
    </section>



    {{-- ================= SERVICES MOBILE ================= --}}
    <section class="py-14 px-6 fade-soft">
        <h2 class="text-2xl font-bold text-center text-[#0f0f0f]">Our Services</h2>

        <div class="w-20 h-[3px] mx-auto mt-3 mb-8 rounded-full"
            style="background: var(--brand-red);"></div>

        <div class="space-y-10">

            {{-- Item --}}
            <div>
                <img src="{{ asset('images/service-indoor.jpg') }}"
                    class="w-full rounded-xl h-48 object-cover shadow">
                <h3 class="mt-3 font-semibold text-lg" style="color: var(--brand-blue);">Indoor Printing</h3>
                <p class="text-gray-600 text-sm">Poster, backdrop, banner, roll-up, dan lainnya.</p>
            </div>

            <div>
                <img src="{{ asset('images/service-outdoor.jpg') }}"
                    class="w-full rounded-xl h-48 object-cover shadow">
                <h3 class="mt-3 font-semibold text-lg" style="color: var(--brand-red);">Outdoor Printing</h3>
                <p class="text-gray-600 text-sm">Billboard, branding, signage luar ruang.</p>
            </div>

            <div>
                <img src="{{ asset('images/service-multi.jpg') }}"
                    class="w-full rounded-xl h-48 object-cover shadow">
                <h3 class="mt-3 font-semibold text-lg" style="color: var(--brand-yellow);">Merch & Multi Product</h3>
                <p class="text-gray-600 text-sm">Stiker, packaging, merchandise, custom produk.</p>
            </div>

        </div>
    </section>



    {{-- ================= CTA MOBILE ================= --}}
    <section class="py-14 px-6 text-center fade-soft">
        <h2 class="text-xl font-bold text-[#0f0f0f]">Ready to Print?</h2>

        <div class="w-20 h-[3px] mx-auto mt-3 mb-4 rounded-full"
            style="background: var(--brand-yellow);"></div>

        <p class="text-gray-600 text-sm max-w-xs mx-auto">
            Tingkatkan citra brand Anda dengan hasil cetak premium.
        </p>

        <a href="{{ route('contact') }}" class="btn-primary mt-6 inline-block px-10 py-3 text-sm">
            Contact Us
        </a>
    </section>

</div>

@endsection
