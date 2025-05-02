<!-- REGISTER PAGE (register.blade.php) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Mahasiswa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#e6f0ff',
                            100: '#cce0ff',
                            200: '#99c2ff',
                            300: '#66a3ff',
                            400: '#3385ff',
                            500: '#0066ff', /* Primary blue color from dashboard */
                            600: '#0052cc',
                            700: '#003d99',
                            800: '#002966',
                            900: '#001433',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex justify-center items-center min-h-screen p-4 font-sans">
    <!-- Header logo/branding -->
    <div class="fixed top-0 left-0 p-4">
        <div class="flex items-center gap-2">
            <div class="bg-brand-500 text-white p-2 rounded-lg">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
            <div class="text-gray-800 font-bold">Sistem Akademik</div>
        </div>
    </div>

    <div class="w-full max-w-md bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="text-center mb-8">
                <div class="inline-block p-3 rounded-full bg-brand-50 mb-3">
                    <i data-lucide="user-plus" class="w-8 h-8 text-brand-500"></i>
                </div>
                <h2 class="text-gray-800 text-2xl font-bold mb-2">Registrasi</h2>
                <p class="text-gray-500 text-sm">Buat Akun Baru</p>
            </div>

            @if($errors->any())
            <div class="bg-red-100 border-red-200 text-red-800 rounded-lg p-3 mb-4 flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('mahasiswa.register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-500">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            placeholder="Masukkan Nama Anda" 
                            required
                            class="w-full pl-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="npm" class="block text-gray-700 font-semibold mb-2">NPM</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-500">
                            <i data-lucide="id-card" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="text" 
                            id="npm" 
                            name="npm" 
                            placeholder="Masukkan NPM Anda" 
                            required
                            class="w-full pl-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-500">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Masukkan Email Anda" 
                            required
                            class="w-full pl-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-500">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan Password" 
                            required
                            class="w-full pl-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                        >
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-brand-500">
                            <i id="passwordIcon" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-500">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Konfirmasi Password" 
                            required
                            class="w-full pl-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200"
                        >
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-brand-500">
                            <i id="confirmPasswordIcon" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white py-2.5 px-4 rounded-lg flex justify-center items-center gap-2 transition-colors shadow-sm hover:shadow">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> Daftar
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">atau</span>
                </div>
            </div>

            <div class="text-center text-sm text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('mahasiswa.login') }}" class="text-brand-500 hover:text-brand-600 font-medium hover:underline">Login di sini</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-0 w-full p-4 text-center text-xs text-gray-500">
        <p class="text-gray-500">&copy; <span id="current-year"></span> Sistem Akademik.</p>
    </div>

    <script>
        document.getElementById("current-year").textContent = new Date().getFullYear();

        // Initialize Lucide icons
        lucide.createIcons();

        // Get references to the password inputs and toggle buttons
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
        const passwordIcon = document.getElementById('passwordIcon');
        const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');

        // Toggle password visibility for the password field
        togglePasswordBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                passwordIcon.setAttribute('data-lucide', 'eye');
            }
            // Re-render the icons after changing the data-lucide attribute
            lucide.createIcons();
        });

        // Toggle password visibility for the confirm password field
        toggleConfirmPasswordBtn.addEventListener('click', function() {
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmPasswordIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                confirmPasswordInput.type = 'password';
                confirmPasswordIcon.setAttribute('data-lucide', 'eye');
            }
            // Re-render the icons after changing the data-lucide attribute
            lucide.createIcons();
        });
    </script>
</body>
</html>