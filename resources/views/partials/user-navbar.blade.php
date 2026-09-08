<header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center">

    <div>

        <h2 class="text-2xl font-bold text-slate-800">

            Dashboard User

        </h2>

    </div>

    <div class="flex items-center gap-4">

        <div class="text-right">

            <p class="font-semibold">

                {{ auth()->user()->name }}

            </p>

            <p class="text-sm text-gray-500">

                {{ auth()->user()->package->name }}

            </p>

        </div>

    </div>

</header>