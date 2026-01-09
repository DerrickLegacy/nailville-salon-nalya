<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kenvies | Loading</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/splash.js'])
    @vite(['resources/css/app.css', 'resources/js/splash.js', 'resources/js/app.js'])

</head>
<style>
    .dot {
        animation: bounce 1.2s infinite ease-in-out;
    }

    .dot:nth-child(2) {
        animation-delay: .15s
    }

    .dot:nth-child(3) {
        animation-delay: .3s
    }

    @keyframes bounce {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }
</style>


<body class="h-screen flex flex-col items-center justify-center bg-gray-50 text-gray-900">

    <!-- Animated SVG container -->
    <div class="relative w-full max-w-xl mb-4 px-4 ">
        <svg viewBox="0 0 450 430" class="w-full h-auto">
            <path
                id="motion-path"
                d="M 215 49 C 132 78 163 204 251 147 C 287 91 221 48 195 96 C 181 157 271 126 234 104"
                 fill="transparent"
                stroke="#8200DB"
                stroke-width="0"
                stroke-linecap="round"
                stroke-dasharray="o"
                stroke-dashoffset="0" />
        </svg>
        <div id="moving-box" class="absolute top-0 left-0 w-6 h-7 md:w-14 md:h-14 bg-emerald-400 rounded-xl"></div>
    </div>

    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#8200DB] text-center mb-2">Nailville Salon Nalya</h1>
    <p class="text-sm sm:text-base md:text-lg text-[#8200DB] text-center mb-6">Opposite Quality Supermarket — Namugongo</p>

    <div class="flex space-x-2 sm:space-x-3 justify-center mb-6">
        <span class="dot w-3 h-3 sm:w-4 sm:h-4 bg-[#8200DB] rounded-full"></span>
        <span class="dot w-3 h-3 sm:w-4 sm:h-4 bg-[#D90082] rounded-full"></span>
        <span class="dot w-3 h-3 sm:w-4 sm:h-4 bg-[#00DB82] rounded-full"></span>
        <span class="dot w-3 h-3 sm:w-4 sm:h-4 bg-[#8200DB] rounded-full"></span>
        <span class="dot w-3 h-3 sm:w-4 sm:h-4 bg-[#D90082] rounded-full"></span>
        <span class="dot w-3 h-3 sm:w-4 sm:h-4 bg-[#00DB82] rounded-full"></span>
    </div>

    <p class="text-sm sm:text-base text-gray-500">Loading...</p>

    <script>
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 6000); 
    </script>

</body>

</html>