@extends('adversting.layouts.app')

@section('content')


{{-- =====================================================
    DESKTOP VERSION (md ke atas)
===================================================== --}}
<div class="hidden md:block">

    {{-- ================= HERO DESKTOP ================= --}}
    <section class="pt-40 pb-20 bg-white text-center fade-soft">
        <div class="max-w-3xl mx-auto px-6">

            <h1 class="hero-title font-extrabold text-[3rem]">
                <span class="hero-blue">Ayo</span>
                <span class="hero-yellow">Hubungi</span>
                <span class="hero-red">Kami</span>
            </h1>

            <p class="mt-5 text-gray-600 text-lg max-w-2xl mx-auto leading-relaxed">
                Tim kami siap membantu kebutuhan percetakan Anda
                dengan pelayanan cepat, ramah, dan hasil berkualitas premium.
            </p>

            <div class="w-32 h-[3px] mx-auto mt-6 rounded-full"
                 style="background: var(--brand-yellow);"></div>
        </div>
    </section>



    {{-- ================= CONTACT FORM DESKTOP ================= --}}
    <section class="py-24 bg-[#fafafa] fade-soft">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-16">

            {{-- ---------- FORM ---------- --}}
            <div class="bg-white rounded-2xl shadow-md p-10">
                <h2 class="text-2xl font-bold text-[#0f0f0f] mb-6">
                    Kirim Pesan
                </h2>

                <form class="space-y-6">

                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text"
                               class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-xl
                               focus:ring-2 focus:ring-[var(--brand-blue)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input type="email"
                               class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-xl
                               focus:ring-2 focus:ring-[var(--brand-blue)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text"
                               class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-xl
                               focus:ring-2 focus:ring-[var(--brand-blue)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Pesan</label>
                        <textarea rows="5"
                                  class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-xl
                                  focus:ring-2 focus:ring-[var(--brand-blue)] focus:border-transparent"></textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3 text-center">
                        Kirim Pesan
                    </button>

                </form>
            </div>



            {{-- ---------- KONTAK INFO ---------- --}}
            <div class="fade-soft">
                <h2 class="text-2xl font-bold text-[#0f0f0f] mb-6">
                    Informasi Kontak
                </h2>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Hubungi kami melalui WhatsApp, email, atau kunjungi kantor terdekat.
                    Kami siap membantu Anda pada jam operasional.
                </p>

                <div class="space-y-7 mb-10">

                    {{-- Phone --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[var(--brand-blue)] flex items-center justify-center text-white text-xl">
                            📞
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Telepon / WhatsApp</h4>
                            <p class="text-gray-600 text-sm mt-1">0821-0000-0000</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[var(--brand-red)] flex items-center justify-center text-white text-xl">
                            ✉️
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">Email</h4>
                            <p class="text-gray-600 text-sm mt-1">restugurupromosindo@gmail.com</p>
                        </div>
                    </div>

                </div>



                {{-- Lokasi --}}
                <h3 class="text-xl font-bold text-[#0f0f0f] mb-4">Lokasi Kantor</h3>

                <ul class="grid grid-cols-2 gap-3 text-gray-600 text-sm">
                    <li class="flex items-center gap-2">📍 Banjarbaru</li>
                    <li class="flex items-center gap-2">📍 Banjar</li>
                    <li class="flex items-center gap-2">📍 Banjarmasin</li>
                    <li class="flex items-center gap-2">📍 Martapura</li>
                    <li class="flex items-center gap-2">📍 Liang Anggang</li>
                    <li class="flex items-center gap-2">📍 Pelaihari</li>
                </ul>

            </div>

        </div>
    </section>



    {{-- ================= MAP DESKTOP ================= --}}
    <section class="pb-24 fade-soft">
        <div class="max-w-6xl mx-auto px-6">
            <div class="rounded-2xl overflow-hidden shadow-md">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31869.224718721645!2d114.5946!3d-3.4411!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de423456789abcd%3A0x123456789abcdef!2sBanjarbaru!5e0!3m2!1sid!2sid!4v1700000000000"
                    width="100%"
                    height="380"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>



    {{-- ================= CTA DESKTOP ================= --}}
    <section class="py-20 bg-white text-center fade-soft">
        <h2 class="text-3xl font-bold text-[#0f0f0f]">Siap Mulai Proyek Anda?</h2>

        <p class="text-gray-600 max-w-xl mx-auto mt-3">
            Konsultasikan kebutuhan Anda dan dapatkan solusi percetakan profesional.
        </p>

        <a href="https://wa.me/6281234567890" class="btn-primary mt-8 inline-block">
            Hubungi via WhatsApp
        </a>
    </section>

</div>







{{-- =====================================================
    MOBILE VERSION (0–md)
===================================================== --}}
<div class="block md:hidden">

    {{-- ================= HERO MOBILE ================= --}}
    <section class="pt-32 pb-14 text-center px-6 fade-soft">

        <h1 class="text-[2rem] font-extrabold leading-tight text-[#0f0f0f]">
            Hubungi Kami
        </h1>

        <p class="text-gray-600 text-base mt-3">
            Siap bantu kebutuhan printing Anda.
        </p>

        <div class="w-20 h-[3px] mx-auto mt-5 rounded-full"
             style="background: var(--brand-yellow);"></div>

    </section>



    {{-- ================= FORM MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft">

        <div class="bg-white rounded-2xl shadow-md p-6">

            <h2 class="text-xl font-bold text-[#0f0f0f] mb-4">Kirim Pesan</h2>

            <form class="space-y-5 text-sm">

                <div>
                    <label class="text-gray-700">Nama Lengkap</label>
                    <input type="text"
                           class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="text-gray-700">Email</label>
                    <input type="email"
                           class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="text-gray-700">Nomor Telepon</label>
                    <input type="text"
                           class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="text-gray-700">Pesan</label>
                    <textarea rows="4"
                              class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-xl"></textarea>
                </div>

                <button class="btn-primary w-full py-3 text-center">
                    Kirim Pesan
                </button>

            </form>

        </div>

    </section>



    {{-- ================= KONTAK INFO MOBILE ================= --}}
    <section class="px-6 py-12 fade-soft space-y-8">

        <h2 class="text-xl font-bold">Informasi Kontak</h2>

        <div class="flex gap-4 items-center">
            <div class="w-11 h-11 flex items-center justify-center bg-[var(--brand-blue)]
                        text-white rounded-xl text-lg">📞</div>
            <div>
                <p class="font-semibold text-sm text-gray-800">Telepon / WhatsApp</p>
                <p class="text-sm text-gray-600">0821-0000-0000</p>
            </div>
        </div>

        <div class="flex gap-4 items-center">
            <div class="w-11 h-11 flex items-center justify-center bg-[var(--brand-red)]
                        text-white rounded-xl text-lg">✉️</div>
            <div>
                <p class="font-semibold text-sm text-gray-800">Email</p>
                <p class="text-sm text-gray-600">restugurupromosindo@gmail.com</p>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="pt-4">
            <h3 class="text-lg font-bold mb-3">Lokasi Kantor</h3>

            <ul class="grid grid-cols-2 gap-3 text-gray-600 text-sm">
                <li>📍 Banjarbaru</li>
                <li>📍 Banjar</li>
                <li>📍 Banjarmasin</li>
                <li>📍 Martapura</li>
                <li>📍 Liang Anggang</li>
                <li>📍 Pelaihari</li>
            </ul>
        </div>

    </section>



    {{-- ================= MAP MOBILE ================= --}}
    <section class="px-6 pb-16 fade-soft">
        <div class="rounded-2xl overflow-hidden shadow-lg">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31869.224718721645!2d114.5946!3d-3.4411!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de423456789abcd%3A0x123456789abcdef!2sBanjarbaru!5e0!3m2!1sid!2sid!4v1700000000000"
                width="100%"
                height="300"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </section>



    {{-- ================= CTA MOBILE ================= --}}
    <section class="py-10 px-6 text-center fade-soft">

        <h2 class="text-xl font-bold text-[#0f0f0f]">Siap Mulai?</h2>

        <p class="text-gray-600 text-sm mt-2 max-w-xs mx-auto">
            Konsultasikan kebutuhan dan dapatkan solusi cetak terbaik.
        </p>

        <a href="https://wa.me/6281234567890" class="btn-primary mt-6 inline-block w-full py-3 text-sm">
            Hubungi via WhatsApp
        </a>

    </section>

</div>



@endsection
