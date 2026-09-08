<aside class="w-64 bg-slate-900 text-white flex flex-col">

    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-slate-700">

        <h1 class="text-2xl font-bold tracking-wide">
            Cloud CCTV
        </h1>

        <p class="text-slate-400 text-sm mt-1">
            User Panel
        </p>

    </div>

    {{-- Menu --}}
    <nav class="flex-1 p-4 space-y-2">

        {{-- Dashboard --}}
        <a href="{{ route('user.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition
           {{ request()->routeIs('user.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">

            🏠 Dashboard

        </a>

        {{-- Kamera Saya --}}
        <a href="{{ route('user.cameras.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition
           {{ request()->routeIs('user.cameras.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">

            📷 Kamera Saya

        </a>

        {{-- Live View --}}
        <a href="{{ route('user.live.grid') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">
           

            📺 Live View

        </a>

        {{-- Playback --}}
        <a href="{{ route('user.playback.index') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">
           

            🎬 Playback

        </a>

        {{-- Storage --}}
        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

            💾 Storage Saya

        </a>

        {{-- Profil --}}
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

            👤 Profil

        </a>

    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-slate-700">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                class="w-full bg-red-600 hover:bg-red-700 rounded-lg py-3">

                Logout

            </button>

        </form>

    </div>

</aside>