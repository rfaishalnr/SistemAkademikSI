<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Skripsi</title>
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
                <a href="{{ route('mahasiswa.pengajuan-kp') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="briefcase" class="mr-1"></i> Kerja Praktek (KP)
                </a>
                <a href="{{ route('mahasiswa.pengajuan-ta') }}" class="flex items-center text-blue-600 font-semibold">
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
                <a href="{{ route('mahasiswa.pengajuan-kp') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="briefcase" class="mr-1"></i> Kerja Praktek (KP)
                </a>
                <a href="{{ route('mahasiswa.pengajuan-ta') }}" class="flex items-center text-blue-600 font-semibold">
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
                <i data-lucide="book-open" class="mr-2 w-4 h-4"></i> Pengajuan Skripsi (TA)
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Sistem Akademik</h1>
            <p class="text-gray-600 mt-2">Ajukan berkas sesuai dengan ketentuan yang telah ditetapkan oleh kampus.</p>
        </div>
    </section>

    <main class="dashboard-main py-8">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6">


{{-- Form Upload Berkas --}}
<form action="{{ route('mahasiswa.submit-ta') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Berkas yang Harus Diupload</h2>

    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
        <table class="w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Berkas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Upload File</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach ($ketentuans as $ketentuan)
                    @if(isset($ketentuan->persyaratan) && !empty(trim($ketentuan->persyaratan)))
                        @php
                            // Ambil file path & status per ketentuan
                            $filePath = $pengajuan->files[$ketentuan->id] ?? null;
                            $fileStatus = $pengajuan->statuses[$ketentuan->id] ?? null;
                            $statusLabelMap = [
                                'accepted' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'pending' => 'Menunggu',
                            ];
                            $isRejected = $fileStatus === 'rejected';
                        @endphp

                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $counter }}</td>
                            <td class="px-4 py-3">{{ $ketentuan->persyaratan }}</td>
                            <td class="px-4 py-3">
                                {{-- Tampilkan status dan link file jika ada --}}
                                @if ($filePath)
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ asset('storage/' . $filePath) }}"
                                            class="text-blue-600 text-sm underline" target="_blank">Lihat
                                            File</a>
                                        <span
                                            class="inline-block text-xs px-2 py-1 rounded 
                                        {{ $fileStatus === 'accepted'
                                            ? 'bg-green-100 text-green-800'
                                            : ($fileStatus === 'rejected'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-gray-100 text-gray-800') }}">
                                            {{ $statusLabelMap[$fileStatus] ?? ucfirst($fileStatus) }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Input file jika belum ada file atau ditolak --}}
                                @if (!$filePath || $isRejected)
                                    <input type="file" name="files[{{ $ketentuan->id }}]"
                                        class="mt-2 block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0 file:text-sm file:font-semibold
                                              file:bg-green-50 file:text-blue-700 hover:file:bg-blue-100"
                                        {{ !$filePath ? 'required' : '' }}>
                                    @if ($isRejected)
                                        <p class="text-sm text-red-600 mt-1">File sebelumnya ditolak.
                                            Silakan unggah ulang.</p>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @php $counter++; @endphp
                    @endif
                @endforeach
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

                {{-- Daftar Pengajuan --}}
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Pengajuan Saya</h2>
                <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                    <table class="w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Berkas
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lihat
                                    Berkas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuans as $index => $pengajuan)
                                @php
                                    $files = is_array($pengajuan->files)
                                        ? $pengajuan->files
                                        : json_decode($pengajuan->files, true) ?? [];

                                    $statuses = is_array($pengajuan->statuses)
                                        ? $pengajuan->statuses
                                        : json_decode($pengajuan->statuses, true) ?? [];

                                    // Pastikan semua status bernilai 'accepted'
                                    $isAllAccepted =
                                        !empty($statuses) && collect($statuses)->every(fn($s) => $s === 'accepted');
                                @endphp

                                @if (!empty($files))
                                    @foreach ($files as $fileIndex => $file)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $fileIndex + 1 }}</td>
                                            <td class="px-4 py-3">
                                                {{ $file['nama_berkas'] ?? basename($file['file']) }}</td>
                                            <td class="px-4 py-3">
                                                <a href="{{ Storage::url($file['file']) }}" target="_blank"
                                                    class="text-blue-600 hover:underline flex items-center">
                                                    <i data-lucide="file" class="mr-1"></i> Lihat Berkas
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $status = $statuses[$fileIndex] ?? 'sent';

                                                    $statusClass = match (strtolower($status)) {
                                                        'sent' => 'bg-yellow-100 text-yellow-800',
                                                        'accepted' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };

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
                                @endif

                                @if ($isAllAccepted)
                                    <tr class="border-b border-gray-100 bg-gray-50">
                                        <td colspan="4" class="px-4 py-4">
                                            @php
                                                $status = strtolower($pengajuan->status_pembimbing ?? 'pending');
                                                $catatan = $pengajuan->catatan_pembimbing;
                                                $statusClass = match ($status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'accepted' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                                $statusLabel = match ($status) {
                                                    'pending' => 'Menunggu Konfirmasi',
                                                    'accepted' => 'Diterima',
                                                    'rejected' => 'Ditolak',
                                                    default => ucfirst($status),
                                                };

                                                $isFirstTime = is_null($pengajuan->dosen_pembimbing_id);
                                                $isRejected = $status === 'rejected';
                                                $canChooseAgain = $isFirstTime || $isRejected;

                                                $dospemDitolak = $isRejected ? $pengajuan->dosen_pembimbing_id : null;

                                                $dosenPembimbing = $pengajuan->dosen_pembimbing_id
                                                    ? $dosens->firstWhere('id', $pengajuan->dosen_pembimbing_id)
                                                    : null;
                                            @endphp


                                            @php
                                                // Status pembimbing 1
                                                $status1 = strtolower($pengajuan->status_pembimbing ?? 'pending');
                                                $isAccepted1 = $status1 === 'accepted';
                                                $isRejected1 = $status1 === 'rejected';

                                                // Status pembimbing 2
                                                $status2 = strtolower($pengajuan->status_pembimbing_2 ?? 'pending');
                                                $isAccepted2 = $status2 === 'accepted';
                                                $isRejected2 = $status2 === 'rejected';

                                                // Determine if student can select supervisors
                                                $canChooseFirstSupervisor = !$isAccepted1 || $isRejected1;
                                                $canChooseSecondSupervisor = !$isAccepted2 || $isRejected2;

                                                // Get supervisor IDs
                                                $dospem1Id = $pengajuan->dosen_pembimbing_id;
                                                $dospem2Id = $pengajuan->dosen_pembimbing_2_id;

                                                // Determine which supervisor was rejected (if any)
                                                $dospem1Ditolak = $isRejected1 ? $dospem1Id : null;
                                                $dospem2Ditolak = $isRejected2 ? $dospem2Id : null;

                                                // Get supervisor objects
                                                $dosenPembimbing1 = $dospem1Id
                                                    ? $dosens->firstWhere('id', $dospem1Id)
                                                    : null;
                                                $dosenPembimbing2 = $dospem2Id
                                                    ? $dosens->firstWhere('id', $dospem2Id)
                                                    : null;
                                            @endphp

<form action="{{ route('mahasiswa.pilih-pembimbing', $pengajuan->id) }}" method="POST">
    @csrf
    <div class="mb-3 space-y-6">
        <!-- Dosen Pembimbing 1 -->
        <div>
            <label for="dosen_pembimbing_id" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Dosen Pembimbing 1
            </label>

            @if ($isAccepted1 && $dosenPembimbing1)
                <!-- Jika dosen pembimbing 1 sudah disetujui, tampilkan informasi saja -->
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 font-medium">Dosen Pembimbing 1 yang disetujui:</span>
                    </div>
                    <div class="mt-2 ml-7 text-green-700 font-medium">
                        {{ $dosenPembimbing1->name }} - {{ $dosenPembimbing1->nidn }}
                    </div>
                    <!-- Tambahkan hidden input untuk menyimpan ID dosen yang sudah disetujui -->
                    <input type="hidden" name="dosen_pembimbing_id" value="{{ $dospem1Id }}">
                </div>
            @elseif ($isRejected1 || !$dospem1Id)
                <!-- Custom Dropdown for Pembimbing 1 -->
                <div id="custom-dropdown-1" class="relative">
                    <div class="custom-select-header cursor-pointer p-3 rounded-lg flex justify-between items-center border border-gray-300 bg-white shadow-sm {{ $isRejected1 ? 'border-red-300' : '' }}">
                        <span id="selected-dosen-1" class="text-gray-700">-- Pilih Dosen Pembimbing 1 --</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="custom-select-options hidden absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-96 overflow-y-auto">
                        <div class="sticky top-0 bg-white shadow-sm z-20">
                            <input type="text" class="search-input p-3 w-full border-b border-gray-200 rounded-t-lg focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Cari dosen...">
                        </div>
                        <div class="py-1">
                            @foreach ($dosens as $dosen)
                                @if (strtolower($dosen->peran) === 'pembimbing')
                                    @if ($dosen->id != $dospem1Ditolak)
                                        <div class="dosen-option p-3 hover:bg-green-50 cursor-pointer" data-value="{{ $dosen->id }}" data-selected="{{ $dospem1Id == $dosen->id ? 'true' : 'false' }}">
                                            <div class="font-medium">{{ $dosen->name }}</div>
                                            <div class="text-sm text-gray-500">NIDN: {{ $dosen->nidn }}</div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="dosen_pembimbing_id" id="dosen_pembimbing_id" value="{{ $dospem1Id }}">
                </div>
            @else
                <!-- Jika pending, tampilkan informasi yang dipilih tetapi tidak bisa diubah -->
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm1-9a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-yellow-700 font-medium">Dosen Pembimbing 1 yang dipilih (menunggu konfirmasi):</span>
                    </div>
                    <div class="mt-2 ml-7 text-yellow-700 font-medium">
                        {{ $dosenPembimbing1->name }} - {{ $dosenPembimbing1->nidn }}
                    </div>
                    <input type="hidden" name="dosen_pembimbing_id" value="{{ $dospem1Id }}">
                </div>
            @endif

            <div class="mt-3">
                @php
                    $statusClass1 = match ($status1) {
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'accepted' => 'bg-green-100 text-green-800 border-green-300',
                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                        default => 'bg-gray-100 text-gray-800 border-gray-300',
                    };

                    $statusLabel1 = match ($status1) {
                        'pending' => 'Menunggu Konfirmasi',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        default => ucfirst($status1),
                    };
                @endphp

                @if ($dospem1Id)
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClass1 }}">
                        Status Pembimbing 1: {{ $statusLabel1 }}
                    </span>
                @endif

                @if ($isRejected1 && $pengajuan->catatan_pembimbing)
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-red-700">Catatan Dosen:</p>
                                <p class="text-sm text-red-600">{{ $pengajuan->catatan_pembimbing }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dosen Pembimbing 2 -->
        <div>
            <label for="dosen_pembimbing_2_id" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Dosen Pembimbing 2
            </label>

            @if ($isAccepted2 && $dosenPembimbing2)
                <!-- Jika dosen pembimbing 2 sudah disetujui, tampilkan informasi saja -->
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 font-medium">Dosen Pembimbing 2 yang disetujui:</span>
                    </div>
                    <div class="mt-2 ml-7 text-green-700 font-medium">
                        {{ $dosenPembimbing2->name }} - {{ $dosenPembimbing2->nidn }}
                    </div>
                    <!-- Tambahkan hidden input untuk menyimpan ID dosen yang sudah disetujui -->
                    <input type="hidden" name="dosen_pembimbing_2_id" value="{{ $dospem2Id }}">
                </div>
            @elseif ($isRejected2 || (!$dospem2Id && ($isAccepted1 || $status1 == 'pending')))
                <!-- Custom Dropdown for Pembimbing 2 -->
                <div id="custom-dropdown-2" class="relative" data-disabled="{{ (!$dospem1Id || $status1 == 'rejected') ? 'true' : 'false' }}">
                    <div class="custom-select-header cursor-pointer p-3 rounded-lg flex justify-between items-center border border-gray-300 bg-white shadow-sm {{ $isRejected2 ? 'border-red-300' : '' }} {{ (!$dospem1Id || $status1 == 'rejected') ? 'opacity-50' : '' }}">
                        <span id="selected-dosen-2" class="text-gray-700">-- Pilih Dosen Pembimbing 2 --</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="custom-select-options hidden absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-96 overflow-y-auto">
                        <div class="sticky top-0 bg-white shadow-sm z-20">
                            <input type="text" class="search-input p-3 w-full border-b border-gray-200 rounded-t-lg focus:outline-none focus:ring-2 focus:ring-green-300" placeholder="Cari dosen...">
                        </div>
                        <div class="py-1">
                            @foreach ($dosens as $dosen)
                                @if (strtolower($dosen->peran) === 'pembimbing')
                                    @if ($dosen->id != $dospem2Ditolak && $dosen->id != $dospem1Id)
                                        <div class="dosen-option p-3 hover:bg-green-50 cursor-pointer" data-value="{{ $dosen->id }}" data-dosen-id="{{ $dosen->id }}" data-selected="{{ $dospem2Id == $dosen->id ? 'true' : 'false' }}">
                                            <div class="font-medium">{{ $dosen->name }}</div>
                                            <div class="text-sm text-gray-500">NIDN: {{ $dosen->nidn }}</div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="dosen_pembimbing_2_id" id="dosen_pembimbing_2_id" value="{{ $dospem2Id }}">
                </div>
            @else
                <!-- Jika pending, tampilkan informasi yang dipilih tetapi tidak bisa diubah -->
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm1-9a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-yellow-700 font-medium">Dosen Pembimbing 2 yang dipilih (menunggu konfirmasi):</span>
                    </div>
                    <div class="mt-2 ml-7 text-yellow-700 font-medium">
                        {{ $dosenPembimbing2->name }} - {{ $dosenPembimbing2->nidn }}
                    </div>
                    <input type="hidden" name="dosen_pembimbing_2_id" value="{{ $dospem2Id }}">
                </div>
            @endif

            <div class="mt-3">
                @php
                    $statusClass2 = match ($status2) {
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'accepted' => 'bg-green-100 text-green-800 border-green-300',
                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                        default => 'bg-gray-100 text-gray-800 border-gray-300',
                    };

                    $statusLabel2 = match ($status2) {
                        'pending' => 'Menunggu Konfirmasi',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        default => ucfirst($status2),
                    };
                @endphp

                @if ($dospem2Id)
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClass2 }}">
                        Status Pembimbing 2: {{ $statusLabel2 }}
                    </span>
                @endif

                @if ($isRejected2 && $pengajuan->catatan_pembimbing_2)
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-red-700">Catatan Dosen:</p>
                                <p class="text-sm text-red-600">{{ $pengajuan->catatan_pembimbing_2 }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        // Tentukan apakah tombol submit perlu ditampilkan
        $showSubmitButton = false;
        
        // Tombol submit ditampilkan jika:
        // - Pembimbing 1 belum dipilih
        // - Pembimbing 1 ditolak dan perlu pemilihan ulang
        // - Pembimbing 2 belum dipilih (dan pembimbing 1 sudah diterima/pending)
        // - Pembimbing 2 ditolak dan perlu pemilihan ulang
        
        if (!$dospem1Id || $isRejected1 || (!$dospem2Id && ($isAccepted1 || $status1 == 'pending')) || $isRejected2) {
            $showSubmitButton = true;
        }
    @endphp

    @if ($showSubmitButton)
        <div class="mt-6">
            <button type="submit" class="w-full md:w-auto bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition-colors shadow-sm flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Dosen Pembimbing
            </button>
        </div>
    @endif
</form>



                                            <!-- Tampilkan kontak dosen jika status accepted -->
                                            @if (($status === 'accepted' && $dosenPembimbing) || ($status2 === 'accepted' && $dosenPembimbing2))
                                                <div class="mt-5 p-4 border border-green-200 bg-green-50 rounded-md">
                                                    <p class="text-sm text-green-800 font-semibold mb-2">
                                                        Kontak Dosen Pembimbing:
                                                    </p>

                                                    <!-- Dosen Pembimbing 1 -->
                                                    @if ($status === 'accepted' && $dosenPembimbing)
                                                        <div class="mb-3 border-b border-green-100 pb-3">
                                                            <p class="text-sm text-green-800 font-semibold">Pembimbing
                                                                1:</p>
                                                            <p class="text-sm text-green-700 mt-1 flex flex-col gap-2">
                                                                <span
                                                                    class="font-medium">{{ $dosenPembimbing->name }}</span>

                                                                <!-- Email -->
                                                                <a href="mailto:{{ $dosenPembimbing->email }}"
                                                                    class="flex items-center text-green-700 hover:text-green-900 underline">
                                                                    <svg class="w-5 h-5 mr-1 text-green-600"
                                                                        fill="none" stroke="currentColor"
                                                                        stroke-width="1.5" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-.947 1.85l-7.5 5.625a2.25 2.25 0 01-2.606 0L3.697 8.843a2.25 2.25 0 01-.947-1.85V6.75" />
                                                                    </svg>
                                                                    {{ $dosenPembimbing->email }}
                                                                </a>

                                                                <!-- WhatsApp -->
                                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dosenPembimbing->nomor_hp) }}"
                                                                    target="_blank"
                                                                    class="flex items-center text-green-700 hover:text-green-900 underline">
                                                                    <svg class="w-5 h-5 mr-1 text-green-600"
                                                                        fill="currentColor" viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M20.52 3.48A11.94 11.94 0 0012 0C5.373 0 0 5.373 0 12a11.94 11.94 0 001.63 5.88L0 24l6.3-1.65A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12 0-3.19-1.237-6.212-3.48-8.52zM12 22.08a10.03 10.03 0 01-5.13-1.41l-.37-.22-3.73.98.99-3.63-.24-.38a10.06 10.06 0 01-1.52-5.35C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10.08-10 10.08zm5.62-7.25c-.3-.15-1.77-.87-2.05-.97s-.47-.15-.67.15-.77.97-.95 1.17-.35.22-.65.07a8.1 8.1 0 01-2.4-1.47 9 9 0 01-1.65-2.05c-.17-.3-.02-.46.13-.6.14-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52s-.67-1.62-.92-2.22c-.24-.57-.48-.5-.67-.5h-.57c-.2 0-.5.07-.77.37s-1 1-1 2.45 1.05 2.85 1.2 3.05 2.06 3.13 5 4.4c.7.3 1.26.48 1.7.61.72.23 1.37.2 1.88.12.58-.08 1.77-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.1-.13-.27-.2-.57-.35z" />
                                                                    </svg>
                                                                    {{ $dosenPembimbing->nomor_hp }}
                                                                </a>
                                                            </p>
                                                        </div>
                                                    @endif

                                                    <!-- Dosen Pembimbing 2 -->
                                                    @if ($status2 === 'accepted' && $dosenPembimbing2)
                                                        <div>
                                                            <p class="text-sm text-green-800 font-semibold">Pembimbing
                                                                2:</p>
                                                            <p class="text-sm text-green-700 mt-1 flex flex-col gap-2">
                                                                <span
                                                                    class="font-medium">{{ $dosenPembimbing2->name }}</span>

                                                                <!-- Email -->
                                                                <a href="mailto:{{ $dosenPembimbing2->email }}"
                                                                    class="flex items-center text-green-700 hover:text-green-900 underline">
                                                                    <svg class="w-5 h-5 mr-1 text-green-600"
                                                                        fill="none" stroke="currentColor"
                                                                        stroke-width="1.5" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-.947 1.85l-7.5 5.625a2.25 2.25 0 01-2.606 0L3.697 8.843a2.25 2.25 0 01-.947-1.85V6.75" />
                                                                    </svg>
                                                                    {{ $dosenPembimbing2->email }}
                                                                </a>

                                                                <!-- WhatsApp -->
                                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dosenPembimbing2->nomor_hp) }}"
                                                                    target="_blank"
                                                                    class="flex items-center text-green-700 hover:text-green-900 underline">
                                                                    <svg class="w-5 h-5 mr-1 text-green-600"
                                                                        fill="currentColor" viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M20.52 3.48A11.94 11.94 0 0012 0C5.373 0 0 5.373 0 12a11.94 11.94 0 001.63 5.88L0 24l6.3-1.65A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12 0-3.19-1.237-6.212-3.48-8.52zM12 22.08a10.03 10.03 0 01-5.13-1.41l-.37-.22-3.73.98.99-3.63-.24-.38a10.06 10.06 0 01-1.52-5.35C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10.08-10 10.08zm5.62-7.25c-.3-.15-1.77-.87-2.05-.97s-.47-.15-.67.15-.77.97-.95 1.17-.35.22-.65.07a8.1 8.1 0 01-2.4-1.47 9 9 0 01-1.65-2.05c-.17-.3-.02-.46.13-.6.14-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52s-.67-1.62-.92-2.22c-.24-.57-.48-.5-.67-.5h-.57c-.2 0-.5.07-.77.37s-1 1-1 2.45 1.05 2.85 1.2 3.05 2.06 3.13 5 4.4c.7.3 1.26.48 1.7.61.72.23 1.37.2 1.88.12.58-.08 1.77-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.1-.13-.27-.2-.57-.35z" />
                                                                    </svg>
                                                                    {{ $dosenPembimbing2->nomor_hp }}
                                                                </a>
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-white py-4 border-t border-gray-200">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500">&copy; <span id="current-year"></span> Sistem Akademik.</p>
        </div>
    </footer>

    <script>
document.addEventListener('DOMContentLoaded', function() {
        // Setup custom dropdowns
        setupCustomDropdowns();
        
        // Initialize other UI elements
        document.getElementById("current-year").textContent = new Date().getFullYear();
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
        
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

    function setupCustomDropdowns() {
        // Setup for Dropdown 1
        const dropdown1 = document.getElementById('custom-dropdown-1');
        if (dropdown1) {
            const header1 = dropdown1.querySelector('.custom-select-header');
            const options1 = dropdown1.querySelector('.custom-select-options');
            const searchInput1 = dropdown1.querySelector('.search-input');
            const hiddenInput1 = document.getElementById('dosen_pembimbing_id');
            const selectedText1 = document.getElementById('selected-dosen-1');
            
            setupDropdown(dropdown1, header1, options1, searchInput1, hiddenInput1, selectedText1);
            
            // Pre-select option if there's a value
            if (hiddenInput1.value) {
                const selectedOption = dropdown1.querySelector(`.dosen-option[data-value="${hiddenInput1.value}"]`);
                if (selectedOption) {
                    selectOption(selectedOption, selectedText1, hiddenInput1);
                }
            }
        }
        
        // Setup for Dropdown 2
        const dropdown2 = document.getElementById('custom-dropdown-2');
        if (dropdown2) {
            const header2 = dropdown2.querySelector('.custom-select-header');
            const options2 = dropdown2.querySelector('.custom-select-options');
            const searchInput2 = dropdown2.querySelector('.search-input');
            const hiddenInput2 = document.getElementById('dosen_pembimbing_2_id');
            const selectedText2 = document.getElementById('selected-dosen-2');
            
            setupDropdown(dropdown2, header2, options2, searchInput2, hiddenInput2, selectedText2);
            
            // Pre-select option if there's a value
            if (hiddenInput2.value) {
                const selectedOption = dropdown2.querySelector(`.dosen-option[data-value="${hiddenInput2.value}"]`);
                if (selectedOption) {
                    selectOption(selectedOption, selectedText2, hiddenInput2);
                }
            }
        }
        
        // Initial update of dropdown states
        updateDropdownStates();
    }
    
    function setupDropdown(dropdown, header, options, searchInput, hiddenInput, selectedText) {
        if (!dropdown || !header || !options || !searchInput || !hiddenInput || !selectedText) return;
        
        // Toggle dropdown on header click
        header.addEventListener('click', function() {
            // Check if dropdown is disabled
            if (dropdown.getAttribute('data-disabled') === 'true') {
                return;
            }
            
            // Close all other dropdowns first
            document.querySelectorAll('.custom-select-options').forEach(opt => {
                if (opt !== options) {
                    opt.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            options.classList.toggle('hidden');
            
            // Focus search input if dropdown is opened
            if (!options.classList.contains('hidden')) {
                searchInput.focus();
            }
        });
        
        // Handle search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const dosenOptions = options.querySelectorAll('.dosen-option');
            
            dosenOptions.forEach(option => {
                const dosenName = option.querySelector('div:first-child').textContent.toLowerCase();
                const dosenNidn = option.querySelector('div:last-child').textContent.toLowerCase();
                
                if (dosenName.includes(searchTerm) || dosenNidn.includes(searchTerm)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });
        
        // Handle option selection
        const dosenOptions = options.querySelectorAll('.dosen-option');
        dosenOptions.forEach(option => {
            option.addEventListener('click', function() {
                selectOption(this, selectedText, hiddenInput);
                options.classList.add('hidden');
                
                // Update dropdown state after selection
                updateDropdownStates();
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target)) {
                options.classList.add('hidden');
            }
        });
    }
    
    function selectOption(option, selectedText, hiddenInput) {
        if (!option || !selectedText || !hiddenInput) return;
        
        // Mark selected option
        const allOptions = option.parentElement.querySelectorAll('.dosen-option');
        allOptions.forEach(opt => {
            opt.setAttribute('data-selected', 'false');
            opt.classList.remove('bg-green-50');
        });
        
        option.setAttribute('data-selected', 'true');
        option.classList.add('bg-green-50');
        
        // Update selected text and hidden input
        const dosenName = option.querySelector('div:first-child').textContent;
        const dosenNidn = option.querySelector('div:last-child').textContent.replace('NIDN: ', '');
        selectedText.textContent = `${dosenName} - ${dosenNidn}`;
        hiddenInput.value = option.getAttribute('data-value');
    }
    
    function updateDropdownStates() {
        const dropdown1 = document.getElementById('custom-dropdown-1');
        const dropdown2 = document.getElementById('custom-dropdown-2');
        const dospem1Id = document.getElementById('dosen_pembimbing_id')?.value;
        
        if (!dropdown1 || !dropdown2) return;
        
        // Enable/disable dropdown 2 based on selection in dropdown 1
        const isDisabled = !dospem1Id;
        dropdown2.setAttribute('data-disabled', isDisabled ? 'true' : 'false');
        
        const header2 = dropdown2.querySelector('.custom-select-header');
        if (header2) {
            if (isDisabled) {
                header2.classList.add('opacity-50');
                header2.classList.add('cursor-not-allowed');
                header2.classList.remove('cursor-pointer');
            } else {
                header2.classList.remove('opacity-50');
                header2.classList.remove('cursor-not-allowed');
                header2.classList.add('cursor-pointer');
            }
        }
        
        // Hide options in dropdown 2 that match the selected option in dropdown 1
        if (dospem1Id && dropdown2) {
            const dosenOptions2 = dropdown2.querySelectorAll('.dosen-option');
            dosenOptions2.forEach(option => {
                if (option.getAttribute('data-dosen-id') === dospem1Id) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        }
    }

        
    </script>
</body>

</html>
