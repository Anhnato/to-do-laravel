<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnyDay Tasks</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(app()->environment('production') || app()->environment('local'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_ID') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ env('GA_ID') }}', {
                debug_mode: {{ app()->environment('local') ? 'true' : 'false' }},
                user_type: '{{ auth()->check() ? "logged_in" : "guest" }}'
            });
        </script>
    @endif

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

        /* Toast Animation Classes - Modified for Bottom positioning */
        .toast-enter {
            transform: translateY(100%);
            opacity: 0;
        }

        .toast-enter-active {
            transform: translateY(0);
            opacity: 1;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-exit {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-exit-active {
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.4s ease-in;
        }
    </style>
</head>

<body class="bg-yellow-50 min-h-screen text-gray-800 font-sans relative" x-data="@yield('alpine-data')" x-cloak>

    <div id="toast-container" style="bottom: 20px; left: 20px;"
        class="fixed z-50 flex flex-col-reverse gap-3 w-auto max-w-sm pointer-events-none">

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.showToast("{{ session('success') }}", 'success');
                });
            </script>
        @endif

        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    @foreach ($errors->all() as $error)
                        window.showToast("{{ $error }}", 'error');
                    @endforeach
                                                    });
            </script>
        @endif
    </div>

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
                <form method="GET" action="{{ route('dashboard') }}" class="relative w-full max-w-md">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-2xl leading-5 bg-white/50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-amber-400 sm:text-sm transition shadow-sm">

                    @if(request('search'))
                        <a href="{{ route('dashboard') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>
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

    <script>
        window.showToast = function (message, type = 'success') {
            const container = document.getElementById('toast-container');

            // Create toast element
            const toast = document.createElement('div');

            // High Contrast Styling: Dark Background with Colored Icons
            // bg-gray-900 (Dark) contrasts well with bg-yellow-50 (Light App)
            const bgColor = type === 'success' ? 'text-green-600' : 'text-red-600';

            const iconColor = type === 'success' ? 'text-yellow-300' : 'text-white';

            toast.className = `${bgColor} text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 pointer-events-auto toast-enter w-auto max-w-[280px]`;

            toast.innerHTML = `
                <i class="fa-solid ${type === 'success' ? 'fa-check' : 'fa-triangle-exclamation'} ${iconColor} text-lg text-green-600"></i>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm leading-tight break-words text-gray-800">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;

            // Append to container
            container.appendChild(toast);

            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-enter-active');
            });

            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.classList.remove('toast-enter-active');
                toast.classList.add('toast-exit-active');
                setTimeout(() => toast.remove(), 500); // Wait for fade out
            }, 4000);
        }

        // 2. SHOW LOADING (New Function)
        window.showLoading = function (message = 'Processing...') {
            const container = document.getElementById('toast-container');

            // Prevent duplicate loading messages
            if (document.getElementById('loading-toast')) return;

            const toast = document.createElement('div');
            toast.id = 'loading-toast'; // Specific ID so we can remove it later

            // Blue background for "Neutral/Working" state
            toast.className = `bg-blue-600 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 pointer-events-auto toast-enter w-auto max-w-[280px]`;

            toast.innerHTML = `
            <i class="fa-solid fa-circle-notch fa-spin text-white text-lg"></i>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-sm leading-tight break-words">${message}</p>
            </div>
        `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-enter-active');
            });
        }

        // 3. HIDE LOADING (New Function)
        window.hideLoading = function () {
            const toast = document.getElementById('loading-toast');
            if (toast) {
                toast.classList.remove('toast-enter-active');
                toast.classList.add('toast-exit-active');
                setTimeout(() => toast.remove(), 500);
            }
        }
    </script>

</body>

</html>
