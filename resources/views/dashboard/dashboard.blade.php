<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <meta name="description" content="Dashboard Akademik Mahasiswa">
    <meta name="author" content="Akademik">
    <meta property="og:image" content="/og-image.png">

    <!-- Using Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <!-- Add custom styles -->
<style>
    .card {
        transition: all 0.3s ease;
    }
    
    @media (max-width: 640px) {
        .guide-item {
            padding: 12px;
        }
        
        .guide-icon svg {
            width: 32px;
            height: 32px;
        }
    }
    
    .guide-item:hover .guide-icon {
        transform: translateY(-5px);
    }
    
    .guide-icon {
        transition: transform 0.3s ease;
    }
    
    /* Tambahan untuk mobile */
    @media (max-width: 480px) {
        .card-container {
            margin-left: -8px;
            margin-right: -8px;
        }
    }
</style>
</head>

<body class="font-sans bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <!-- Kiri: Logo -->
            <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center text-gray-800 hover:text-gray-600">
                <i data-lucide="graduation-cap" class="mr-2"></i>
                <span class="font-semibold">Dashboard Mahasiswa</span>
            </a>

            <!-- Tengah: Navigasi (hidden di mobile) -->
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center text-blue-600 font-semibold">
                    <i data-lucide="home" class="mr-1"></i> Dashboard
                </a>
                <a href="{{ route('mahasiswa.pengajuan-kp') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
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
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center text-blue-600 font-semibold">
                    <i data-lucide="home" class="mr-1"></i> Dashboard
                </a>
                <a href="{{ route('mahasiswa.pengajuan-kp') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
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



    <section class="bg-gray-50 py-8 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full mb-2">
                <i data-lucide="home" class="mr-2 w-4 h-4"></i> Dashboard
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Sistem Akademik</h1>
            @auth('mahasiswa')
                <p class="text-gray-600 mt-2">Selamat datang,
                    <strong>{{ Auth::guard('mahasiswa')->user()->name }}</strong>.
                </p>
            @endauth
        </div>
    </section>


    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Notifikasi & Info KP --}}
            @php
                use App\Models\PengajuanKP;
                use App\Models\PengajuanSkripsi;
                use App\Models\LaporanKP;
                use App\Models\Ketentuan;
                $pengajuanKP = PengajuanKP::where('mahasiswa_id', auth()->id())
                    ->latest()
                    ->first();
                $pengajuanTA = PengajuanSkripsi::with('dosen_pembimbing') // <-- ini penting
                    ->where('mahasiswa_id', auth()->id())
                    ->latest()
                    ->first();
                $laporanKP = LaporanKP::where('mahasiswa_id', auth()->id())
                    ->latest()
                    ->first();
            @endphp

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Kerja Praktek (KP) -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-blue-600 px-6 py-4 border-b border-blue-1000">
                        <h3 class="text-xl font-semibold text-white">Kerja Praktek (KP)</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Kerja Praktek (KP) adalah program wajib bagi mahasiswa untuk
                            mendapatkan pengalaman kerja sebelum mengambil skripsi.</p>

                        <!-- Berkas yang harus diajukan -->
                        {{-- <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Berkas yang Harus Diajukan:</h4>
                            <ul class="list-disc pl-5 text-gray-600 space-y-1">
                                @forelse($ketentuans ?? [] as $ketentuan)
                                    <li>{{ $ketentuan->persyaratan }}</li>
                                @empty
                                    <li class="text-gray-500 italic">Belum ada ketentuan berkas untuk KP.</li>
                                @endforelse
                            </ul>
                        </div> --}}

                        <!-- Status pengajuan KP -->
                        @if ($pengajuanKP)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Status Pengajuan:</h4>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    @php
                                        // Periksa tipe data sebelum decode
                                        $files = is_array($pengajuanKP->files)
                                            ? $pengajuanKP->files
                                            : json_decode($pengajuanKP->files, true) ?? [];

                                        $statuses = is_array($pengajuanKP->statuses)
                                            ? $pengajuanKP->statuses
                                            : json_decode($pengajuanKP->statuses, true) ?? [];
                                    @endphp

                                    @if (count($files) > 0)
                                        <ul class="space-y-2">
                                            @foreach ($files as $index => $file)
                                                <li class="flex justify-between items-center text-sm">
                                                    <span class="truncate flex-1">
                                                        @if (is_string($file))
                                                            {{ pathinfo($file, PATHINFO_BASENAME) }}
                                                        @else
                                                            {{ is_array($file) ? basename(json_encode($file)) : 'Invalid file' }}
                                                        @endif
                                                    </span>
                                                    <div class="flex items-center ml-2">
                                                        <a href="{{ asset('storage/' . (is_string($file) ? $file : '')) }}"
                                                            target="_blank"
                                                            class="text-blue-600 hover:text-blue-800 mr-2">
                                                            <i data-lucide="file" class="w-4 h-4"></i>
                                                        </a>
                                                        @php
                                                            $status = $statuses[$index] ?? 'sent';
                                                            $statusClass = match (strtolower($status)) {
                                                                'sent' => 'bg-yellow-100 text-yellow-800',
                                                                'accepted' => 'bg-green-100 text-green-800',
                                                                'rejected' => 'bg-red-100 text-red-800',
                                                                default => 'bg-gray-100 text-gray-800',
                                                            };
                                                            $statusLabel = match (strtolower($status)) {
                                                                'sent' => 'Terkirim',
                                                                'accepted' => 'Diterima',
                                                                'rejected' => 'Ditolak',
                                                                default => ucfirst($status),
                                                            };
                                                        @endphp
                                                        <span
                                                            class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500 italic">Belum ada berkas yang diunggah.</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Info Laporan KP -->
                        @if ($laporanKP)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Laporan KP:</h4>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <div class="space-y-2 text-sm">
                                        <p class="flex justify-between">
                                            <span class="font-medium">Nama File:</span>
                                            <span class="text-gray-700">
                                                @if (is_string($laporanKP->file))
                                                    {{ basename($laporanKP->file) }}
                                                @else
                                                    {{ is_array($laporanKP->file) ? 'Multiple files' : 'Invalid file' }}
                                                @endif
                                            </span>
                                        </p>
                                        <p class="flex justify-between">
                                            <span class="font-medium">Lihat Laporan:</span>
                                            <a href="{{ asset('storage/' . (is_string($laporanKP->file) ? $laporanKP->file : '')) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:text-blue-800 flex items-center">
                                                <i data-lucide="file-text" class="w-4 h-4 mr-1"></i> Lihat
                                            </a>
                                        </p>
                                        <p class="flex justify-between">
                                            <span class="font-medium">Nilai:</span>
                                            @if ($laporanKP->nilai)
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                    {{ $laporanKP->nilai }}
                                                </span>
                                            @else
                                                <span class="text-yellow-600 italic">Belum dinilai</span>
                                            @endif
                                        </p>
                                        @if ($laporanKP->catatan)
                                            <div>
                                                <span class="font-medium">Komentar Dosen:</span>
                                                <p
                                                    class="text-gray-700 mt-1 bg-white p-2 rounded border border-gray-200">
                                                    {{ $laporanKP->catatan }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <a href="{{ route('mahasiswa.pengajuan-kp') }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center w-max">
                                <i data-lucide="edit" class="mr-2 w-4 h-4"></i> Kelola KP
                            </a>
                        </div>
                    </div>
                </div>

<!-- Skripsi -->
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="bg-blue-600 px-6 py-4 border-b border-blue-1000">
        <h3 class="text-xl font-semibold text-white">Skripsi (TA)</h3>
    </div>
    <div class="p-6">
        <p class="text-gray-600 mb-4">Skripsi adalah tugas akhir yang harus diselesaikan oleh mahasiswa
            untuk memperoleh gelar akademik.</p>

        <!-- Status pengajuan Skripsi -->
        @if ($pengajuanTA)
            <div class="mb-4">
                <h4 class="font-semibold text-gray-700 mb-2">Status Pengajuan:</h4>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    @php
                        // Periksa tipe data sebelum decode
                        $filesData = is_array($pengajuanTA->files)
                            ? $pengajuanTA->files
                            : json_decode($pengajuanTA->files, true) ?? [];

                        $statuses = is_array($pengajuanTA->statuses)
                            ? $pengajuanTA->statuses
                            : json_decode($pengajuanTA->statuses, true) ?? [];

                        // Cek apakah semua file sudah diterima
                        $isAllAccepted =
                            !empty($statuses) && collect($statuses)->every(fn($s) => $s === 'accepted');
                    @endphp

                    @if (count($filesData) > 0)
                        <ul class="space-y-2">
                            @foreach ($filesData as $index => $fileData)
                                <li class="flex justify-between items-center text-sm">
                                    @php
                                        // Handle the complex file structure
                                        $filePath = '';
                                        $fileName = 'Invalid file';

                                        if (
                                            is_array($fileData) &&
                                            isset($fileData['file']) &&
                                            isset($fileData['nama_berkas'])
                                        ) {
                                            $filePath = $fileData['file'];
                                            $fileName = $fileData['nama_berkas'];
                                        } elseif (is_string($fileData)) {
                                            $filePath = $fileData;
                                            $fileName = pathinfo($fileData, PATHINFO_BASENAME);
                                        }
                                    @endphp

                                    <span class="truncate flex-1">{{ $fileName }}</span>
                                    <div class="flex items-center ml-2">
                                        <a href="{{ asset('storage/' . $filePath) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 mr-2">
                                            <i data-lucide="file" class="w-4 h-4"></i>
                                        </a>
                                        @php
                                            $status = $statuses[$index] ?? 'sent';
                                            $statusClass = match (strtolower($status)) {
                                                'sent' => 'bg-yellow-100 text-yellow-800',
                                                'accepted' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                            $statusLabel = match (strtolower($status)) {
                                                'sent' => 'Terkirim',
                                                'accepted' => 'Diterima',
                                                'rejected' => 'Ditolak',
                                                default => ucfirst($status),
                                            };
                                        @endphp
                                        <span
                                            class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Belum ada berkas yang diunggah.</p>
                    @endif
                </div>
            </div>

            <!-- Informasi Dosen Pembimbing -->
            @if ($isAllAccepted)
                @php
                    // Pembimbing 1
                    $hasDosen1 = !is_null($pengajuanTA->dosen_pembimbing_id);
                    $statusPembimbing1 = $hasDosen1
                        ? strtolower($pengajuanTA->status_pembimbing ?? 'pending')
                        : 'pending';
                    $catatanPembimbing1 = $pengajuanTA->catatan_pembimbing;
                    $statusClass1 = match ($statusPembimbing1) {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'accepted' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                    $statusLabel1 = match ($statusPembimbing1) {
                        'pending' => $hasDosen1 ? 'Menunggu Konfirmasi' : 'Belum Memilih Dosen',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        default => ucfirst($statusPembimbing1),
                    };
                    $dosenPembimbing1 = $pengajuanTA->dosen_pembimbing ?? null;
                    
                    // Pembimbing 2
                    $hasDosen2 = !is_null($pengajuanTA->dosen_pembimbing_2_id);
                    $statusPembimbing2 = $hasDosen2
                        ? strtolower($pengajuanTA->status_pembimbing_2 ?? 'pending')
                        : 'pending';
                    $catatanPembimbing2 = $pengajuanTA->catatan_pembimbing_2;
                    $statusClass2 = match ($statusPembimbing2) {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'accepted' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                    $statusLabel2 = match ($statusPembimbing2) {
                        'pending' => $hasDosen2 ? 'Menunggu Konfirmasi' : 'Belum Memilih Dosen',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        default => ucfirst($statusPembimbing2),
                    };
                    $dosenPembimbing2 = $pengajuanTA->dosen_pembimbing_2 ?? null;
                @endphp

                <!-- Pembimbing 1 -->
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Dosen Pembimbing 1:</h4>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-2">
                            <i data-lucide="user" class="w-5 h-5 text-blue-600 mr-2"></i>
                            <span>{{ $dosenPembimbing1 ? $dosenPembimbing1->name : 'Belum memilih dosen pembimbing 1' }}</span>

                            <span
                                class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $statusClass1 }} ml-2">
                                {{ $statusLabel1 }}
                            </span>
                        </div>

                        @if ($statusPembimbing1 === 'rejected' && $catatanPembimbing1)
                            <p class="text-sm text-red-600 mt-2">
                                <strong>Catatan Dosen:</strong> {{ $catatanPembimbing1 }}
                            </p>
                        @endif

                        <!-- Tampilkan kontak dosen jika status accepted dan dosen sudah dipilih -->
                        @if ($statusPembimbing1 === 'accepted' && $dosenPembimbing1)
                            <div class="mt-3 p-3 border border-green-200 bg-green-50 rounded-md">
                                <p class="text-sm text-green-800 font-semibold">
                                    Kontak Dosen Pembimbing 1:
                                </p>
                                <p class="text-sm text-green-700 mt-1 flex flex-col gap-2">
                                    <span class="font-medium">{{ $dosenPembimbing1->name }}</span>

                                    <!-- Email -->
                                    <a href="mailto:{{ $dosenPembimbing1->email }}"
                                        class="flex items-center text-green-700 hover:text-green-900 underline">
                                        <svg class="w-5 h-5 mr-1 text-green-600" fill="none"
                                            stroke="currentColor" stroke-width="1.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-.947 1.85l-7.5 5.625a2.25 2.25 0 01-2.606 0L3.697 8.843a2.25 2.25 0 01-.947-1.85V6.75" />
                                        </svg>
                                        {{ $dosenPembimbing1->email }}
                                    </a>

                                    <!-- WhatsApp -->
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dosenPembimbing1->nomor_hp) }}"
                                        target="_blank"
                                        class="flex items-center text-green-700 hover:text-green-900 underline">
                                        <svg class="w-5 h-5 mr-1 text-green-600" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M20.52 3.48A11.94 11.94 0 0012 0C5.373 0 0 5.373 0 12a11.94 11.94 0 001.63 5.88L0 24l6.3-1.65A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12 0-3.19-1.237-6.212-3.48-8.52zM12 22.08a10.03 10.03 0 01-5.13-1.41l-.37-.22-3.73.98.99-3.63-.24-.38a10.06 10.06 0 01-1.52-5.35C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10.08-10 10.08zm5.62-7.25c-.3-.15-1.77-.87-2.05-.97s-.47-.15-.67.15-.77.97-.95 1.17-.35.22-.65.07a8.1 8.1 0 01-2.4-1.47 9 9 0 01-1.65-2.05c-.17-.3-.02-.46.13-.6.14-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52s-.67-1.62-.92-2.22c-.24-.57-.48-.5-.67-.5h-.57c-.2 0-.5.07-.77.37s-1 1-1 2.45 1.05 2.85 1.2 3.05 2.06 3.13 5 4.4c.7.3 1.26.48 1.7.61.72.23 1.37.2 1.88.12.58-.08 1.77-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.1-.13-.27-.2-.57-.35z" />
                                        </svg>
                                        {{ $dosenPembimbing1->nomor_hp }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

<!-- Pembimbing 2 -->
<div class="mb-4">
    <h4 class="font-semibold text-gray-700 mb-2">Dosen Pembimbing 2:</h4>
    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
        <div class="flex items-center mb-2">
            <i data-lucide="user" class="w-5 h-5 text-blue-600 mr-2"></i>
            <span>{{ $dosenPembimbing2 ? $dosenPembimbing2->name : 'Belum memilih dosen pembimbing 2' }}</span>

            @if ($hasDosen2)
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $statusClass2 }} ml-2">
                    {{ $statusLabel2 }}
                </span>
            @endif
        </div>

        @if ($statusPembimbing2 === 'rejected' && $catatanPembimbing2)
            <p class="text-sm text-red-600 mt-2">
                <strong>Catatan Dosen:</strong> {{ $catatanPembimbing2 }}
            </p>
        @endif

        <!-- Tampilkan kontak dosen jika status accepted dan dosen sudah dipilih -->
        @if ($statusPembimbing2 === 'accepted' && $dosenPembimbing2)
            <div class="mt-3 p-3 border border-green-200 bg-green-50 rounded-md">
                <p class="text-sm text-green-800 font-semibold">
                    Kontak Dosen Pembimbing 2:
                </p>
                <p class="text-sm text-green-700 mt-1 flex flex-col gap-2">
                    <span class="font-medium">{{ $dosenPembimbing2->name }}</span>

                    <!-- Email -->
                    <a href="mailto:{{ $dosenPembimbing2->email }}"
                        class="flex items-center text-green-700 hover:text-green-900 underline">
                        <svg class="w-5 h-5 mr-1 text-green-600" fill="none"
                            stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-.947 1.85l-7.5 5.625a2.25 2.25 0 01-2.606 0L3.697 8.843a2.25 2.25 0 01-.947-1.85V6.75" />
                        </svg>
                        {{ $dosenPembimbing2->email }}
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dosenPembimbing2->nomor_hp) }}"
                        target="_blank"
                        class="flex items-center text-green-700 hover:text-green-900 underline">
                        <svg class="w-5 h-5 mr-1 text-green-600" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M20.52 3.48A11.94 11.94 0 0012 0C5.373 0 0 5.373 0 12a11.94 11.94 0 001.63 5.88L0 24l6.3-1.65A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12 0-3.19-1.237-6.212-3.48-8.52zM12 22.08a10.03 10.03 0 01-5.13-1.41l-.37-.22-3.73.98.99-3.63-.24-.38a10.06 10.06 0 01-1.52-5.35C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10.08-10 10.08zm5.62-7.25c-.3-.15-1.77-.87-2.05-.97s-.47-.15-.67.15-.77.97-.95 1.17-.35.22-.65.07a8.1 8.1 0 01-2.4-1.47 9 9 0 01-1.65-2.05c-.17-.3-.02-.46.13-.6.14-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52s-.67-1.62-.92-2.22c-.24-.57-.48-.5-.67-.5h-.57c-.2 0-.5.07-.77.37s-1 1-1 2.45 1.05 2.85 1.2 3.05 2.06 3.13 5 4.4c.7.3 1.26.48 1.7.61.72.23 1.37.2 1.88.12.58-.08 1.77-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.1-.13-.27-.2-.57-.35z" />
                        </svg>
                        {{ $dosenPembimbing2->nomor_hp }}
                    </a>
                </p>
            </div>
        @endif
    </div>
</div>



            @elseif ($pengajuanTA->dosen_id)
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Dosen Pembimbing:</h4>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 text-blue-600 mr-2"></i>
                            <span>{{ $pengajuanTA->dosen->nama ?? 'Dosen belum ditugaskan' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div>
            <a href="{{ route('mahasiswa.pengajuan-ta') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center w-max">
                <i data-lucide="edit" class="mr-2 w-4 h-4"></i> Kelola TA
            </a>
        </div>
    </div>
</div>



            </div>
        </div>

        
    </main>

<!-- Panduan & Template Section -->
<section class="bg-gray-50 py-6 sm:py-8 md:py-10">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Panduan & Template</h2>
        
        @php
            // Fetch ketentuan data from database
            $ketentuans = App\Models\Ketentuan::whereNotNull('panduan')
                ->whereNotNull('file_panduan')
                ->get();
            
            // Group ketentuan by jenis (KP and Skripsi)
            $kpTemplates = $ketentuans->where('jenis', 'KP');
            $skripsiTemplates = $ketentuans->where('jenis', 'Skripsi');
        @endphp

        <!-- KP Guides Card - Only shown if there are KP templates -->
        @if($kpTemplates->count() > 0)
        <div class="card bg-white shadow-lg rounded-lg overflow-hidden mb-4 sm:mb-6">
            <div class="bg-blue-600 px-4 sm:px-6 py-3 sm:py-4 flex items-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-lg sm:text-xl font-semibold">Panduan Kerja Praktek (KP)</h3>
            </div>
            <div class="p-3 sm:p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($kpTemplates as $template)
                    <!-- Template Item -->
                    <a href="{{ asset('storage/' . $template->file_panduan) }}" target="_blank" class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200 hover:bg-gray-100 transition flex flex-col items-center text-center group">
                        <div class="text-blue-600 mb-2 sm:mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">{{ $template->panduan }}</h4>
                        <span class="text-blue-600 hover:text-blue-800 flex items-center justify-center text-xs sm:text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Template
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Skripsi Guides Card - Only shown if there are Skripsi templates -->
        @if($skripsiTemplates->count() > 0)
        <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 px-4 sm:px-6 py-3 sm:py-4 flex items-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-lg sm:text-xl font-semibold">Panduan Skripsi (TA)</h3>
            </div>
            <div class="p-3 sm:p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($skripsiTemplates as $template)
                    <!-- Template Item -->
                    <a href="{{ asset('storage/' . $template->file_panduan) }}" target="_blank" class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200 hover:bg-gray-100 transition flex flex-col items-center text-center group">
                        <div class="text-blue-600 mb-2 sm:mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">{{ $template->panduan }}</h4>
                        <span class="text-blue-600 hover:text-blue-800 flex items-center justify-center text-xs sm:text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Template
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Show a message if no templates are available -->
        @if($ketentuans->count() == 0)
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg text-center">
                <p class="text-gray-600 text-sm sm:text-base">Belum ada template yang tersedia. Silakan cek kembali nanti.</p>
            </div>
        @endif
    </div>
</section>



    
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
