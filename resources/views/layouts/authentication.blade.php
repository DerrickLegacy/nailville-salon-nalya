<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <script>
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>
</head>

<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">

    <main class="bg-white dark:bg-gray-900">

        <div class="relative flex">

            <!-- Content -->
            <div class="w-full md:w-1/2">

                <div class="min-h-[100dvh] h-full flex flex-col after:flex-1">

                    <!-- Header -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between h-20 sm:h-24 lg:h-32 px-4 sm:px-4 lg:px-8 mt-2">
                            <!-- Logo -->
                            <a class="block" href="{{ route('dashboard') }}">
                                <img class="object-cover object-center w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-full border-2 border-purple-600"
                                    src="{{ asset('images/nailville_logo_100x100.jpg') }}" alt="Authentication image" />
                            </a>
                        </div>
                    </div>
                    <div class="max-w-sm mx-auto w-full px-4 py-8">
                        {{ $slot }}
                    </div>

                </div>

            </div>

            <!-- Image -->
            <?php
            $images = ['images/fahrad-norouzi-Udy1IKvjeJU-unsplash.jpg', 'images/auth-image.jpg', 'images/junior-reis-doXuH8GbUns-unsplash.jpg', 'images/nailville_logo.svg', 'images/facial_tools.png'];
            ?>

            <div class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2" aria-hidden="true">
                <div class="swiper mySwiper w-full h-full">
                    <div class="swiper-wrapper">
                        @foreach ($images as $image)
                            <div class="swiper-slide">
                                <img class="object-cover object-center w-full h-full" src="{{ asset($image) }}"
                                    alt="Authentication image" />
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

        </div>

    </main>

    @livewireScriptConfig
</body>

</html>


<!-- SwiperJS CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- SwiperJS JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 3000,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>
