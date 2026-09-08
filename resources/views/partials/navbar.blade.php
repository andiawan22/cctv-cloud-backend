<header class="navbar h-16 flex items-center justify-between px-6">

    <h2 class="text-xl font-semibold">
        Dashboard
    </h2>

    <div class="flex items-center gap-3">

        <span>{{ Auth::user()->name }}</span>

    </div>

</header>