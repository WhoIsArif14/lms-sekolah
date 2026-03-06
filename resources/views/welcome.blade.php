<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudiFy - Platform Pembelajaran Online</title>
    <link rel="stylesheet" href="/build/assets/app-haV-1hLA.css">
    <script src="/build/assets/app-CBBTb_k3.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        .wave-container {
            position: relative;
            background: #3b82f6;
            height: 80px;
        }

        .wave-container::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 80px;
            background: white;
            clip-path: ellipse(60% 100% at 50% 100%);
        }
    </style>
</head>

<body class="bg-white text-gray-900 antialiased">

    <nav class="absolute top-0 w-full z-50 px-6 py-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <span class="text-white font-black text-2xl tracking-tighter italic">Studi<span
                        class="text-blue-200">Fy</span></span>
            </div>

            <div class="hidden md:flex items-center gap-8 text-white/90 font-bold text-sm uppercase tracking-widest">
                <a href="#" class="hover:text-white transition">Home</a>
                <a href="#analisis" class="hover:text-white transition">Analisis</a>
                <a href="#fitur" class="hover:text-white transition">Fitur</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="bg-white text-blue-600 px-8 py-3 rounded-2xl font-black shadow-xl hover:bg-blue-50 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-blue-500/20 backdrop-blur-md border border-white/30 text-white px-8 py-3 rounded-2xl font-black hover:bg-white hover:text-blue-600 transition">Masuk
                            Sistem</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="hero-gradient pt-40 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="text-white space-y-8 z-10">
                <div
                    class="inline-block px-4 py-1.5 bg-blue-400/20 backdrop-blur-md border border-white/10 rounded-full">
                    <span class="text-xs font-black uppercase tracking-widest text-blue-100 italic">🚀 Modern Learning
                        Platform</span>
                </div>
                <h1 class="text-6xl md:text-7xl font-black leading-[1.1] italic tracking-tighter">
                    Sistem Pembelajaran Online
                </h1>
                <p class="text-blue-100 text-xl font-medium leading-relaxed max-w-lg">
                    Platform LMS cerdas untuk mendukung transisi digital SMKN 1 Jatirejo dengan fitur analisis real-time
                    dan monitoring orang tua.
                </p>
                <div class="flex flex-wrap gap-4">
                    <button
                        class="bg-yellow-400 text-blue-900 px-10 py-5 rounded-2xl font-black text-sm shadow-2xl hover:bg-yellow-300 transition uppercase tracking-widest">
                        Mulai Sekarang
                    </button>
                    <a href="#analisis"
                        class="bg-white/10 backdrop-blur-md text-white px-10 py-5 rounded-2xl font-black text-sm border border-white/20 hover:bg-white/20 transition uppercase tracking-widest">
                        Lihat Analisis
                    </a>
                </div>
            </div>

            <div class="relative group">
                <div
                    class="absolute -inset-4 bg-gradient-to-r from-yellow-400 to-blue-400 rounded-[3rem] blur-2xl opacity-20 animate-pulse">
                </div>

                <div class="relative bg-white p-3 rounded-[3rem] shadow-2xl">
                    <div class="aspect-video bg-slate-900 rounded-[2.2rem] overflow-hidden relative shadow-inner">
                        <iframe class="w-full h-full"
                            src="https://www.youtube.com/embed/CWdIuBkfL_A?si=kLdxiKqFbT1Ti3Ey"
                            title="LMS Video Preview" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <div class="absolute -top-10 -right-10 hidden lg:block animate-bounce">
                    <div class="bg-yellow-400 p-6 rounded-full shadow-2xl border-4 border-white">
                        <svg class="w-10 h-10 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wave-container"></div>

    <section id="analisis" class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black text-gray-900 mb-4 italic uppercase tracking-tighter">Analisis Capaian
                    Siswa</h2>
                <div class="w-24 h-2 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="bg-gray-50 p-12 rounded-[3rem] border border-gray-100 shadow-sm">
                    <h3 class="text-2xl font-black mb-10 flex items-center gap-4 italic text-gray-800">
                        <span class="p-3 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </span>
                        Metrik Utama
                    </h3>

                    <div class="space-y-10">
                        <div>
                            <div class="flex justify-between mb-3 items-end">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Rerata
                                    Akademik</span>
                                <span class="text-3xl font-black text-blue-600">{{ round($avgAcademic) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full transition-all duration-1000"
                                    style="width: {{ $avgAcademic }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-3 items-end">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Tingkat
                                    Kehadiran</span>
                                <span class="text-3xl font-black text-green-500">{{ round($attendanceRate) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-green-500 h-4 rounded-full transition-all duration-1000"
                                    style="width: {{ $attendanceRate }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-3 items-end">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Indeks
                                    Kedisiplinan</span>
                                <span class="text-3xl font-black text-amber-500">99%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 text-center">
                                <div class="bg-amber-500 h-4 rounded-full" style="width: 99%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 p-12 rounded-[3rem] shadow-2xl text-white relative">
                    <h3 class="text-2xl font-black mb-3 flex items-center gap-4 italic">
                        <span class="p-3 bg-red-500 text-white rounded-2xl shadow-lg shadow-red-900/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </span>
                        Atensi Khusus
                    </h3>
                    <p class="text-slate-400 font-medium mb-10">Sistem mendeteksi indikasi penurunan performa bulan
                        ini.</p>

                    <div class="space-y-5">
                        <div
                            class="flex items-center justify-between p-6 bg-white/5 rounded-[2rem] border border-white/10 hover:bg-white/10 transition group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-red-500/20 flex items-center justify-center text-red-500 font-black text-2xl group-hover:scale-110 transition">
                                    !</div>
                                <div>
                                    <p class="text-lg font-bold">Butuh Bimbingan Nilai</p>
                                    <p class="text-sm text-slate-500 italic">{{ $lowScoreCount }} Siswa di bawah KKM
                                    </p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>

                        <div
                            class="flex items-center justify-between p-6 bg-white/5 rounded-[2rem] border border-white/10 hover:bg-white/10 transition group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-500 font-black text-2xl group-hover:scale-110 transition">
                                    ?</div>
                                <div>
                                    <p class="text-lg font-bold">Stabilitas Presensi</p>
                                    <p class="text-sm text-slate-500 italic">{{ $lowAttendanceCount }} Siswa sering
                                        absen</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-12 p-8 bg-blue-600 rounded-[2rem] shadow-xl relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-xs font-black mb-2 uppercase tracking-[0.3em] text-blue-200">Tips Sistem</p>
                            <p class="text-sm font-semibold leading-relaxed">Gunakan fitur "Push Notification" untuk
                                memberikan peringatan dini langsung ke aplikasi orang tua.</p>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 border-t border-gray-100 text-center">
        <p class="text-sm text-gray-400 font-medium tracking-widest uppercase">
            &copy; {{ date('Y') }} StudiFy &bull; Mohammad Faizin SMKN 1 Jatirejo Mojokerto
        </p>
    </footer>

</body>

</html>
