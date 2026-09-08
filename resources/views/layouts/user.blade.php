<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-slate-100">

<div class="flex h-screen">

    @include('partials.user-sidebar')

    <div class="flex flex-col flex-1">

        @include('partials.user-navbar')

        <main class="flex-1 overflow-y-auto p-6">

            @yield('content')

        </main>

        @include('partials.footer')

    </div>

</div>

</body>

</html>