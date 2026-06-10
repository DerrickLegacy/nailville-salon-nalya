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
    <!-- Header -->
    <div class="flex items-center justify-between h-20 sm:h-24 lg:h-32 px-4 sm:px-4 lg:px-8 mt-2">
        <!-- Logo -->
        <a class="block" href="{{ route('dashboard') }}">
            <img class="object-cover object-center w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-full border-2 border-purple-600"
                src="{{ asset('images/nailville_logo_100x100.jpg') }}" alt="Authentication image" />
        </a>
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
    <p>Loading ...</p>

    <script>
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 6000);
    </script>

</body>

</html>