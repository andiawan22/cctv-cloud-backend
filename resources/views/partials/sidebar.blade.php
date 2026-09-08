<aside class="sidebar flex flex-col">



    <!-- Logo -->

    <div class="sidebar-header">



        <div class="flex items-center gap-3">



            <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white text-xl font-bold">

                C

            </div>



            <div>



                <h1 class="sidebar-title">

                    CLOUD CCTV

                </h1>



                <p class="sidebar-subtitle">

                    Management System

                </p>



            </div>



        </div>



    </div>



    <!-- Menu -->

    <nav class="sidebar-menu flex-1">



        <a href="{{ route('admin.dashboard') }}"

   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">



    <svg xmlns="http://www.w3.org/2000/svg"

         class="w-5 h-5 mr-3"

         fill="none"

         viewBox="0 0 24 24"

         stroke="currentColor">



        <path stroke-linecap="round"

              stroke-linejoin="round"

              stroke-width="2"

              d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H5m7 0h7"/>



    </svg>



    Dashboard



</a>



        <a href="{{ route('admin.users.index') }}"

   class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">



    <svg xmlns="http://www.w3.org/2000/svg"

         class="w-5 h-5 mr-3"

         fill="none"

         viewBox="0 0 24 24"

         stroke="currentColor">



        <path stroke-linecap="round"

              stroke-linejoin="round"

              stroke-width="2"

              d="M17 20h5V18a4 4 0 00-4-4h-1m-4 6H6a4 4 0 01-4-4v-2a4 4 0 014-4h8a4 4 0 014 4v2a4 4 0 01-4 4zm-4-10a4 4 0 100-8 4 4 0 000 8z"/>



    </svg>



    Manajemen User



</a>

        <a href="{{ route('admin.packages.index') }}"

   class="sidebar-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">



    <svg xmlns="http://www.w3.org/2000/svg"

         class="w-5 h-5 mr-3"

         fill="none"

         viewBox="0 0 24 24"

         stroke="currentColor">



        <path stroke-linecap="round"

              stroke-linejoin="round"

              stroke-width="2"

              d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>



    </svg>



    Paket Layanan



</a>



        <a href="{{ route('admin.cameras.index') }}"

            class="sidebar-link {{ request()->routeIs('admin.cameras.*') ? 'active' : '' }}">



    <svg xmlns="http://www.w3.org/2000/svg"

         class="w-5 h-5 mr-3"

         fill="none"

         viewBox="0 0 24 24"

         stroke="currentColor">



        <path stroke-linecap="round"

              stroke-linejoin="round"

              stroke-width="2"

              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14v4a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v4z"/>



    </svg>



    Kamera



</a>

        <a href="{{ route('admin.storage.index') }}"

            class="sidebar-link {{ request()->routeIs('admin.storage.*') ? 'active' : '' }}">



    <svg xmlns="http://www.w3.org/2000/svg"

         class="w-5 h-5 mr-3"

         fill="none"

         viewBox="0 0 24 24"

         stroke="currentColor">



        <path stroke-linecap="round"

              stroke-linejoin="round"

              stroke-width="2"

              d="M5 5h14v4H5V5zm0 5h14v4H5v-4zm0 5h14v4H5v-4z"/>



    </svg>



    Storage Server



</a>

        <!-- MENU MONITORING CCTV (Yang sudah diperbaiki) -->

        <a href="{{ route('admin.live.grid') }}" class="sidebar-link">

            <svg xmlns="http://www.w3.org/2000/svg"

                 class="w-5 h-5 mr-3"

                 fill="none"

                 viewBox="0 0 24 24"

                 stroke="currentColor">

                <path stroke-linecap="round"

                      stroke-linejoin="round"

                      stroke-width="2"

                      d="M3 15a4 4 0 014-4h10a4 4 0 014 4M7 15v2m10-2v2M9 11V7m6 4V7"/>

            </svg>

            Monitoring CCTV

        </a>



        <!-- MENU LOG AKTIVITAS (Dikembalikan tag <a> pembukanya) -->

        <a href="#" class="sidebar-link">

            <svg xmlns="http://www.w3.org/2000/svg"

                 class="w-5 h-5 mr-3"

                 fill="none"

                 viewBox="0 0 24 24"

                 stroke="currentColor">

                <path stroke-linecap="round"

                      stroke-linejoin="round"

                      stroke-width="2"

                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>

            </svg>

            Log Aktivitas

        </a>



       <a href="#" class="sidebar-link">



    <svg xmlns="http://www.w3.org/2000/svg"

         class="w-5 h-5 mr-3"

         fill="none"

         viewBox="0 0 24 24"

         stroke="currentColor">



        <path stroke-linecap="round"

              stroke-linejoin="round"

              stroke-width="2"

              d="M12 8c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm8.94 4a7.96 7.96 0 00-.17-1.64l2.03-1.58-2-3.46-2.39.96a8.15 8.15 0 00-2.84-1.65L15.2 2h-4.4l-.37 2.63a8.15 8.15 0 00-2.84 1.65l-2.39-.96-2 3.46 2.03 1.58A7.96 7.96 0 003.06 12c0 .56.06 1.11.17 1.64L1.2 15.22l2 3.46 2.39-.96c.84.71 1.8 1.28 2.84 1.65L10.8 22h4.4l.37-2.63a8.15 8.15 0 002.84-1.65l2.39.96 2-3.46-2.03-1.58c.11-.53.17-1.08.17-1.64z"/>



    </svg>



    Pengaturan



</a>



    </nav>



    <!-- Logout -->

    <div class="p-4 border-t border-slate-700">



        <form method="POST" action="{{ route('logout') }}">

            @csrf



            <button

                class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg py-2 transition">



                Logout



            </button>



        </form>



    </div>



</aside> 

