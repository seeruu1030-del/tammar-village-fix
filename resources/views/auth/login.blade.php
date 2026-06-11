<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | T-Link Smart Management System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>
<body class="bg-slate-50 min-h-screen flex flex-col md:flex-row overflow-x-hidden">

    <!-- SISI KIRI: BRANDING -->
    <div class="hidden md:flex md:w-1/2 lg:w-3/5 login-branding relative overflow-hidden items-center justify-center p-12">
        <div class="absolute top-0 left-0 w-full h-full opacity-20 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-sky-500 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 text-center max-w-lg">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-sky-400 to-blue-600 mb-8 shadow-2xl accent-glow">
                <span class="text-white text-5xl font-extrabold italic">T</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4">
                T-Link <span class="text-sky-400">System</span>
            </h1>
            <p class="text-slate-400 text-lg lg:text-xl font-medium leading-relaxed mb-8">
                The Tamar Village Smart Management & Integrated Payment System.
            </p>
        </div>
        
        <div class="absolute bottom-8 left-12 text-slate-500 text-xs font-medium">
            &copy; 2026 PT. Tamar Inovasi Teknologi
        </div>
    </div>

    <!-- SISI KANAN: FORM LOGIN -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12 lg:p-20 bg-white">
        <div class="w-full max-w-md">
            <div class="md:hidden flex items-center gap-3 mb-10">
                <div class="w-10 h-10 rounded-xl bg-sky-600 flex items-center justify-center text-white font-bold text-xl">T</div>
                <span class="text-2xl font-bold text-slate-800 tracking-tight text-xl">T-Link <span class="text-sky-600 font-normal">Admin</span></span>
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Selamat Datang</h2>
                <p class="text-slate-500 font-medium">Silakan masuk ke akun portal Anda untuk melanjutkan.</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Error Message Container -->
                @if($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold p-3 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <!-- Username / Email / NIK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Username atau NIK</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="login" placeholder="admin / 3201xxxx..." required value="{{ old('login') }}"
                            class="block w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                        <a href="#" class="text-[11px] font-bold text-sky-600 hover:text-sky-700">Lupa Sandi?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" placeholder="••••••••" required
                            class="block w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i id="passIcon" class="fa-solid fa-eye-slash text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-slate-300 rounded cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-600 font-medium cursor-pointer">Ingat saya di perangkat ini</label>
                </div>

                <div>
                    <button type="submit" id="loginBtn"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-all transform active:scale-[0.98]">
                        MASUK KE DASHBOARD
                    </button>
                </div>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm text-slate-500 font-medium">Belum memiliki akun? <a href="#" class="text-sky-600 font-bold hover:underline">Hubungi Pengurus</a></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const passIcon = document.getElementById('passIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                passIcon.classList.remove('fa-eye-slash');
                passIcon.classList.add('fa-eye');
            } else {
                passInput.type = 'password';
                passIcon.classList.remove('fa-eye');
                passIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>
