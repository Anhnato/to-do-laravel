<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnyDay Tasks</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YWE8TTC2GK"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-YWE8TTC2GK');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .modal-bg {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.3);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-yellow-50 min-h-screen text-gray-800 font-sans" x-data="@yield('alpine-data')" x-cloak>

    <nav
        class="sticky top-0 z-40 bg-yellow-50/80 backdrop-blur-md border-b border-amber-100/50 shadow-sm transition-all duration-300">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-6 py-4 md:px-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-amber-600 drop-shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-sun"></i> <span class="hidden md:inline">SunnyDay</span>
            </h1>

            <div class="flex-1 max-w-md relative group mx-4">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i
                        class="fa-solid fa-magnifying-glass text-gray-400 group-focus-within:text-amber-500 transition"></i>
                </div>
                <input type="text" x-model="search" placeholder="Search tasks..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-2xl leading-5 bg-white/50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 sm:text-sm transition shadow-sm">
            </div>

            <div class="flex gap-3 items-center">
                <button @click="view = (view === 'grid' ? 'list' : 'grid')"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-2 px-3 md:py-3 md:px-4 rounded-2xl shadow-lg transition transform hover:scale-105 border border-gray-100"
                    title="Switch View">
                    <i class="fa-solid text-amber-500" :class="view === 'grid' ? 'fa-list' : 'fa-border-all'"></i>
                </button>

                @auth
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit"
                            class="bg-white hover:bg-red-50 text-gray-800 hover:text-red-500 font-bold py-2 px-4 md:py-3 md:px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2 border border-gray-100">
                            <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-2 px-4 md:py-3 md:px-6 rounded-2xl shadow-lg transition transform hover:scale-105 flex items-center gap-2 border border-gray-100">
                        <i class="fa-solid fa-right-to-bracket"></i> <span class="hidden md:inline">Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    @stack('scripts')

</body>

</html>