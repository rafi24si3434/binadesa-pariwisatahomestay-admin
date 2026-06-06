<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register | Pariwisata & Homestay</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Fade animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-card {
            animation: fadeInUp .7s ease-out;
        }

        /* Soft background motion */
        .bg-animate {
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>

<body
    class="bg-animate bg-gradient-to-br from-red-50 via-white to-red-100 min-h-screen flex items-center justify-center px-4">

    <!-- CARD -->
    <div
        class="fade-card bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl w-full max-w-md border border-red-100">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" class="w-32 drop-shadow-md">
        </div>

        <!-- TITLE -->
        <h2 class="text-2xl font-extrabold text-center mb-2 text-red-700">
            Daftar Akun Baru
        </h2>

        <p class="text-center text-gray-600 mb-6 text-sm">
            Buat akun untuk mengakses sistem pariwisata & homestay
        </p>

        <!-- ERROR VALIDATION -->
        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('register.process') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">Nama Lengkap</label>
                <input type="text" name="name" required
                    class="w-full border border-red-200 px-3 py-2 rounded-lg
                           focus:ring-2 focus:ring-red-400 focus:outline-none transition bg-white/80">
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">Email</label>
                <input type="email" name="email" required
                    class="w-full border border-red-200 px-3 py-2 rounded-lg
                           focus:ring-2 focus:ring-red-400 focus:outline-none transition bg-white/80">
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">Password</label>
                <input type="password" name="password" required minlength="6"
                    class="w-full border border-red-200 px-3 py-2 rounded-lg
                           focus:ring-2 focus:ring-red-400 focus:outline-none transition bg-white/80">
                <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
            </div>

            <!-- Role -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">Role</label>
                <select name="role" required
                    class="w-full border border-red-200 px-3 py-2 rounded-lg bg-white
                           focus:ring-2 focus:ring-red-400 focus:outline-none transition">
                    <option value="admin">Admin</option>
                    <option value="petugas" selected>Petugas</option>
                </select>
            </div>

            <!-- SUBMIT -->
            <button type="submit"
                class="w-full py-2 rounded-lg font-semibold shadow-lg text-white text-lg
                       transition active:scale-95"
                style="background:#C62828;">
                Daftar Akun
            </button>
        </form>

        <!-- LOGIN LINK -->
        <p class="text-center mt-6 text-gray-700 text-sm">
            Sudah punya akun?
            <a href="{{ route('login.form') }}" class="font-semibold text-red-600 hover:underline">
                Login
            </a>
        </p>

    </div>

</body>
</html>
