<!-- LOGIN PAGE (login.blade.php) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa</title>
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
                    <i data-lucide="user" class="w-8 h-8 text-brand-500"></i>
                </div>
                <h2 class="text-gray-800 text-2xl font-bold mb-2">Login</h2>
                <p class="text-gray-500 text-sm">Masuk ke Sistem Akademik</p>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border-green-200 text-green-800 rounded-lg p-3 mb-4 flex items-start gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border-red-200 text-red-800 rounded-lg p-3 mb-4 flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('mahasiswa.login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="npm" class="block text-gray-700 font-semibold mb-2">NPM</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-500">
                            <i data-lucide="user" class="w-5 h-5"></i>
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

                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <label for="password" class="block text-gray-700 font-semibold">Password</label>
                        {{-- <a href="#" class="text-xs text-brand-500 hover:text-brand-600">Lupa password?</a> --}}
                    </div>
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
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white py-2.5 px-4 rounded-lg flex justify-center items-center gap-2 transition-colors shadow-sm hover:shadow">
                    <i data-lucide="log-in" class="w-5 h-5"></i> Login
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
                Belum punya akun? 
                <a href="{{ route('mahasiswa.register') }}" class="text-brand-500 hover:text-brand-600 font-medium hover:underline">Daftar di sini</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-0 w-full p-4 text-center text-xs text-gray-500">
        <p class="text-gray-500">&copy; <span id="current-year"></span> Sistem Akademik.</p>
    </div>

    <script>
        document.getElementById("current-year").textContent = new Date().getFullYear();
        lucide.createIcons();

        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePassword');

        togglePasswordBtn.addEventListener('click', function() {
            // Toggle password field visibility
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            
            // Clear the current icon
            this.innerHTML = '';
            
            // Create new icon element based on password visibility state
            const newIcon = document.createElement('i');
            newIcon.setAttribute('data-lucide', passwordInput.type === 'password' ? 'eye' : 'eye-off');
            this.appendChild(newIcon);
            
            // Re-initialize Lucide icons
            lucide.createIcons();
        });
    </script>
</body>
</html>