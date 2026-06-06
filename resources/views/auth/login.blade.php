<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login | Pariwisata & Homestay</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        /* ============================
           BACKGROUND SLIDESHOW FIX
        ============================ */
        .bg-slideshow {
            position: fixed;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
            filter: brightness(65%);
            transition: opacity 1.5s ease-in-out;
        }

        /* Fade-in for card */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-card {
            animation: fadeIn .8s ease-out;
        }

        /* Particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            filter: blur(4px);
            animation: floatUp 8s infinite ease-in-out;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(0);
                opacity: .6;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translateY(-80px);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen relative">

    <!-- BACKGROUND -->
    <div id="bgImage" class="bg-slideshow"></div>

    <!-- PARTICLES -->
    <span class="particle" style="width:40px; height:40px; left:10%; bottom:10%; animation-duration:10s;"></span>
    <span class="particle" style="width:28px; height:28px; left:70%; bottom:12%; animation-duration:12s;"></span>
    <span class="particle" style="width:34px; height:34px; left:48%; bottom:9%; animation-duration:9s;"></span>

    <!-- LOGIN CARD -->
    <div
        class="fade-card bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl w-full max-w-md border border-red-100 relative">

        <!-- LOGO -->
        <div class="flex justify-center mb-3">
            <img src="{{ asset('assets/images/logo.png') }}" class="w-60 drop-shadow-xl">
        </div>

        <!-- INTRO -->
        <div class="text-center mb-6">
            <h2 class="text-xl font-extrabold text-red-700">
                Sistem Pariwisata & Homestay
            </h2>

            <p class="text-gray-700 text-sm mt-2">
                Aplikasi pengelolaan destinasi wisata, homestay, kamar, dan booking secara terpadu.
                Silakan masuk untuk melanjutkan.
            </p>
        </div>

        <!-- ERROR -->
        @if (session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="font-semibold text-gray-700 text-sm">Email</label>
                <input type="email" name="email" required
                    class="w-full border border-red-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-red-400 bg-white/80">
            </div>

            <div>
                <label class="font-semibold text-gray-700 text-sm">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-red-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-red-400 bg-white/80">
            </div>

            <!-- LOGIN -->
            <button class="w-full py-2 rounded-lg font-semibold shadow-lg text-white text-lg"
                style="background:#C62828;">
                Masuk
            </button>

            <!-- GOOGLE DISABLED -->
            <button type="button" disabled
                class="w-full py-2 rounded-lg font-semibold shadow-md text-gray-400 bg-gray-200 flex items-center justify-center gap-3 cursor-not-allowed opacity-80">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
                Login dengan Google (Coming Soon)
            </button>
        </form>

        <!-- REGISTER -->
        <p class="text-center mt-6 text-gray-700 text-sm">
            Belum punya akun?
            <a href="{{ route('register.form') }}" class="font-semibold text-red-600 hover:underline">
                Daftar Akun
            </a>
        </p>
    </div>

    <!-- BACKGROUND SCRIPT -->
    <script>
        const bgImages = [
            "https://images.unsplash.com/photo-1506744038136-46273834b3fb",
            "https://images.unsplash.com/photo-1507525428034-b723cf961d3e",
            "https://images.unsplash.com/photo-1526772662000-3f88f10405ff",
            "https://images.unsplash.com/photo-1501785888041-af3ef285b470",
            "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee",
            "https://images.unsplash.com/photo-1541417904950-b855846fe074",
        ];

        let index = 0;
        const bgElement = document.getElementById("bgImage");

        function changeBackground() {
            bgElement.style.opacity = 0;
            setTimeout(() => {
                bgElement.style.backgroundImage = `url('${bgImages[index]}')`;
                bgElement.style.opacity = 1;
                index = (index + 1) % bgImages.length;
            }, 1000);
        }

        changeBackground();
        setInterval(changeBackground, 6000);
    </script>

</body>
</html>
