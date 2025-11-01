<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- site map -->
    <meta name="google-site-verification" content="NxXiaY1odI6lPdxfwtvYHkE1VIEI2bI2gJ9Vt0XZ1R0" />

    @php
    $pageTitle = $title ?? null;
    $fullTitle = $pageTitle
    ? config('app.name', 'Nailville Salon') . ' - ' . $pageTitle
    : config('app.name', 'Nailville Salon');
    $pageDescription = $metaDescription ?? 'Nailville Salon is a unisex salon in Naalya opposite Quality Shopping Village Namugongo, offering beauty, hair, nail and spa services for men, women, and children.';
    $canonicalUrl = url()->current();
    $previewImage = asset('images/preview.png'); // Replace with your actual preview image path
    @endphp

    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="Nailville, salon, beauty, hair, spa, nails, Kampala, Naalya, Uganda">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/preview.png')}}">

    <!-- Open Graph Meta Tags (for Facebook, WhatsApp, LinkedIn, etc.) -->
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $previewImage }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="Nailville Salon">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $fullTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $previewImage }}">
    <meta name="twitter:site" content="@NailvilleSalon"> <!-- Replace with your real handle -->
    <meta name="twitter:creator" content="@NailvilleSalon">

    <!-- Structured Data (Organization Schema for Google Rich Results) -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Nailville Salon",
            "url": "https://staging-nailville-salon.kenvies.com",
            "logo": "https://staging-nailville-salon.kenvies.com/salon.png",
            "sameAs": [
                "https://www.facebook.com/nailvillesalon",
                "https://www.instagram.com/nailvillesalon"
            ],
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+256-700-000000",
                "contactType": "customer service",
                "areaServed": "UG",
                "availableLanguage": ["English"]
            },
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Naalya, opposite Quality Shopping Village Namugongo",
                "addressLocality": "Kampala",
                "addressCountry": "UG"
            }
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.tailwindcss.js"></script>
    {{-- sweet alert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Javascript pdf export --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    {{-- Morris chart --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.tailwindcss.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    <!-- Styles -->
    @livewireStyles
    @filamentStyles
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
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <style>
        body {
            /* font-size: smaller; */
            /* font-size: 10px */
            font-size: medium
        }

        h3 {
            font-size-adjust: smaller;
        }

        /* Top wrapper (length + search) */
        .dt-layout-row:first-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            /* like mb-4 */
        }

        /* Bottom wrapper (info + pagination) */
        .dt-layout-row:last-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            /* like mt-4 */
        }

        /* Style search input */
        .dt-search input {
            @apply border border-gray-300 rounded-md px-3 py-1 text-sm;
        }

        /* Style page length dropdown */
        .dt-length select {
            @apply border border-gray-300 rounded-md px-2 py-1 text-sm;
        }

        /* Pagination buttons */
        .dt-paging button {
            @apply px-3 py-1 border rounded-md mx-1 text-sm hover:bg-gray-200;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .info-group {
            transition: background-color 0.2s ease;
        }

        .info-group:hover {
            background-color: #f9fafb;
        }

        /* Ensure info is on the left and pagination on the right */
        .dataTables_wrapper .dataTables_info {
            float: left !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
        }
    </style>

</head>

<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400"
    :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <!-- Page wrapper -->
    <div class="flex h-[100dvh] overflow-hidden">

        <x-app.sidebar :variant="$attributes['sidebarVariant']" />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if ($attributes['background']) {{ $attributes['background'] }} @endif"
            x-ref="contentarea">

            <x-app.header :variant="$attributes['headerVariant']" />
            @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                class="bg-red-100 text-red-700 p-4 rounded mb-4 transition duration-500">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <main class="grow">
                {{ $slot }}

                @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
                @endif


            </main>

        </div>

    </div>

    @livewireScriptConfig
    {{-- {{ $slot ?? '' }} --}}
    {{-- @filamentScripts --}}
    {{-- @livewireScripts --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>