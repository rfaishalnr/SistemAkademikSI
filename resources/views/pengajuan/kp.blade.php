<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Kerja Praktek</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
</head>

<body class="dashboard-page font-sans">
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <!-- Kiri: Logo -->
            <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center text-gray-800 hover:text-gray-600">
                <i data-lucide="graduation-cap" class="mr-2"></i>
                <span class="font-semibold">Dashboard Mahasiswa</span>
            </a>

            <!-- Tengah: Navigasi (hidden di mobile) -->
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('mahasiswa.dashboard') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="home" class="mr-1"></i> Dashboard
                </a>
                <a href="{{ route('mahasiswa.pengajuan-kp') }}" class="flex items-center text-blue-600 font-semibold">
                    <i data-lucide="briefcase" class="mr-1"></i> Kerja Praktek (KP)
                </a>
                <a href="{{ route('mahasiswa.pengajuan-ta') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="book-open" class="mr-1"></i> Skripsi (TA)
                </a>
            </nav>

            <!-- Kanan: User menu + hamburger -->
            <div class="flex items-center space-x-4">
                <!-- User dropdown -->
                @auth('mahasiswa')
                    <div class="relative hidden md:block">
                        <button class="flex items-center bg-gray-100 px-3 py-2 rounded-full" id="userMenuBtn">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-2">
                                {{ strtoupper(substr(Auth::guard('mahasiswa')->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline">{{ Auth::guard('mahasiswa')->user()->name }}</span>
                            <i data-lucide="chevron-down" class="ml-1"></i>
                        </button>

                        <div id="userDropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-20">

                            <form action="{{ route('mahasiswa.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center">
                                    <i data-lucide="log-out" class="mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Hamburger -->
                <button class="md:hidden" id="mobileMenuBtn">
                    <i data-lucide="menu"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileNav" class="hidden md:hidden px-4 pb-4">
            <nav class="flex flex-col space-y-2">
                <a href="{{ route('mahasiswa.dashboard') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="home" class="mr-1"></i> Dashboard
                </a>
                <a href="{{ route('mahasiswa.pengajuan-kp') }}" class="flex items-center text-blue-600 font-semibold">
                    <i data-lucide="briefcase" class="mr-1"></i> Kerja Praktek (KP)
                </a>
                <a href="{{ route('mahasiswa.pengajuan-ta') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="book-open" class="mr-1"></i> Skripsi (TA)
                </a>

                <!-- User menu mobile -->
                @auth('mahasiswa')
                    <div class="border-t pt-2 mt-2">
                        <div class="flex items-center bg-gray-100 px-3 py-2 rounded-full">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-2">
                                {{ strtoupper(substr(Auth::guard('mahasiswa')->user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::guard('mahasiswa')->user()->name }}</span>
                        </div>
                        <form action="{{ route('mahasiswa.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center">
                                <i data-lucide="log-out" class="mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </nav>
        </div>
    </header>



    <section class="page-header bg-gray-50 py-8 text-center">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="header-badge inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full mb-2">
                <i data-lucide="briefcase" class="mr-2 w-4 h-4"></i> Pengajuan Kerja Praktek (KP)
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Sistem Akademik</h1>
            <p class="text-gray-600 mt-2">Ajukan berkas sesuai dengan ketentuan yang telah ditetapkan oleh kampus.</p>
        </div>
    </section>

    <main class="dashboard-main py-8">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Berkas yang Harus Diupload</h2>

                <form action="{{ route('mahasiswa.submit-kp') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                        <table class="w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Berkas</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Upload File</th>
                                    {{-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panduan</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $validKetentuans = $ketentuans->filter(function($ketentuan) {
                                        return isset($ketentuan->persyaratan) && !empty(trim($ketentuan->persyaratan));
                                    });
                                @endphp
                                
                                @if($validKetentuans->count() > 0)
                                    @php $counter = 1; @endphp
                                    @foreach($validKetentuans as $ketentuan)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $counter }}</td>
                                            <td class="px-4 py-3">{{ $ketentuan->persyaratan }}</td>
                                            <td class="px-4 py-3">
                                                <input type="file" name="files[{{ $ketentuan->id }}]"
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                                    required>
                                            </td>
                                            {{-- <td class="px-4 py-3">
                                            @if ($ketentuan->file_panduan)
                                                <a href="{{ asset('storage/' . $ketentuan->file_panduan) }}" target="_blank" class="text-blue-600 hover:underline">
                                                    Lihat Panduan
                                                </a>
                                            @else
                                                Tidak ada panduan
                                            @endif
                                        </td> --}}
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">Tidak ada berkas
                                            yang perlu diunggah.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                
                    <div class="mt-6">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i data-lucide="upload" class="mr-2"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>

                <hr class="my-8 border-gray-200">

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Pengajuan Saya</h2>
                <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Berkas</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lihat Berkas</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($pengajuans as $pengajuan)
                                @php
                                    $files = is_array($pengajuan->files)
                                        ? $pengajuan->files
                                        : json_decode($pengajuan->files, true) ?? [];
                                    $statuses = is_array($pengajuan->statuses)
                                        ? $pengajuan->statuses
                                        : json_decode($pengajuan->statuses, true) ?? [];
                                @endphp



                                @if (!empty($files))
                                    @foreach ($files as $index => $file)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                            <!-- No Urut -->
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {{ pathinfo($file, PATHINFO_BASENAME) }}</td>
                                            <!-- Nama Berkas -->
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                    class="text-blue-600 hover:underline flex items-center">
                                                    <i data-lucide="file" class="mr-1"></i> Lihat Berkas
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $status = $statuses[$index] ?? 'sent';

                                                    $statusClass = match (strtolower($status)) {
                                                        'sent' => 'bg-yellow-100 text-yellow-800',
                                                        'accepted' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };

                                                    // Terjemahan status
                                                    $statusLabelMap = [
                                                        'sent' => 'Terkirim',
                                                        'accepted' => 'Diterima',
                                                        'rejected' => 'Ditolak',
                                                    ];

                                                    $translatedStatus =
                                                        $statusLabelMap[strtolower($status)] ?? ucfirst($status);
                                                @endphp

                                                <span
                                                    class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                                    {{ $translatedStatus }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">Belum ada
                                            pengajuan yang dikirim.</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>




                    </table>
                </div>

                <hr class="my-8 border-gray-200">

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Laporan Kerja Praktek</h2>

                <form action="{{ route('mahasiswa.upload-laporan-kp') }}" method="POST"
                    enctype="multipart/form-data" class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File Laporan (PDF, maks
                            2MB):</label>
                        <input type="file" name="file" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                        <i data-lucide="upload" class="mr-2 w-4 h-4"></i> Upload Laporan
                    </button>
                </form>

                @php
                    $laporan = \App\Models\LaporanKp::where('mahasiswa_id', Auth::guard('mahasiswa')->id())
                        ->latest()
                        ->first();
                @endphp

                @if ($laporan)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Laporan Kerja Praktek
                            </h3>

                            @if ($laporan->nilai)
                                @if ($laporan->nilai < 60)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Nilai: {{ $laporan->nilai }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Nilai: {{ $laporan->nilai }}
                                    </span>
                                @endif
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Menunggu Penilaian
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <!-- File Preview -->
                            <div
                                class="flex-shrink-0 bg-gray-50 border border-gray-100 rounded-lg p-4 w-full sm:w-48 flex flex-col items-center justify-center text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div class="mt-2 text-xs text-gray-500 truncate w-full"
                                    title="{{ basename($laporan->file) }}">
                                    {{ basename($laporan->file) }}
                                </div>
                            </div>

                            <!-- File Details -->
                            <div class="flex-1 space-y-4">
                                <!-- Download Link -->
                                <div class="flex items-center gap-4">
                                    <a href="{{ asset('storage/' . $laporan->file) }}" target="_blank"
                                        class="flex items-center justify-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-4 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Laporan
                                    </a>

                                    {{-- <a href="{{ asset('storage/' . $laporan->file) }}" download
                                        class="flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a> --}}
                                </div>

                                @if ($laporan->catatan)
                                    <div class="mt-4 bg-gray-50 border-l-4 border-blue-500 rounded-r-lg p-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">Catatan dari Dosen:</h4>
                                        <p class="text-gray-600">{{ $laporan->catatan }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        @if ($laporan->updated_at)
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>
                                        Diunggah pada: {{ $laporan->created_at->format('d M Y, H:i') }}
                                        @if ($laporan->created_at->ne($laporan->updated_at))
                                            | Terakhir diperbarui: {{ $laporan->updated_at->format('d M Y, H:i') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif



            </div>
        </div>
    </main>

    <footer class="bg-white py-4 border-t border-gray-200">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500">&copy; <span id="current-year"></span> Sistem Akademik.</p>
        </div>
    </footer>

    <script>
        document.getElementById("current-year").textContent = new Date().getFullYear();
        lucide.createIcons();

        document.addEventListener("DOMContentLoaded", function() {
            // User dropdown
            const userMenuBtn = document.getElementById("userMenuBtn");
            const userDropdown = document.getElementById("userDropdown");

            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener("click", function(event) {
                    event.stopPropagation();
                    userDropdown.classList.toggle("hidden");
                });

                document.addEventListener("click", function(event) {
                    if (!userMenuBtn.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add("hidden");
                    }
                });
            }

            // Mobile nav toggle
            const mobileMenuBtn = document.getElementById("mobileMenuBtn");
            const mobileNav = document.getElementById("mobileNav");

            if (mobileMenuBtn && mobileNav) {
                mobileMenuBtn.addEventListener("click", function() {
                    mobileNav.classList.toggle("hidden");
                });
            }
        });
    </script>
</body>

</html>
