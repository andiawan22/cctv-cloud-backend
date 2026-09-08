<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Cloud CCTV')</title>
    <!-- Google Font : Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-slate-100">

    <div class="flex h-screen">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Area kanan --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Konten --}}
            <main class="flex-1 overflow-y-auto p-6">

                @yield('content')

            </main>

            {{-- Footer --}}
            @include('partials.footer')

        </div>

    </div>

</body>
</html>