<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SunnyDay Tasks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-yellow-50 min-h-screen flex items-center justify-center p-6 text-gray-800 font-sans">

    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl max-w-md w-full border border-amber-100 relative">

        <a href="{{ route('dashboard') }}" class="absolute top-6 left-6 text-gray-400 hover:text-amber-600 transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-amber-600 mb-2">
                Join SunnyDay
            </h1>
            <p class="text-gray-500">Create your account to start organizing.</p>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 pl-1">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" placeholder="John Doe"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 pl-1">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" placeholder="you@example.com"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 pl-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" placeholder="Create a password"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 pl-1">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" placeholder="Confirm password"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 ring-amber-400 outline-none transition">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-amber-500 text-white py-4 rounded-xl hover:bg-amber-600 font-bold shadow-lg shadow-amber-200 transition transform hover:scale-[1.02] mt-4">
                Create Account
            </button>
        </form>

        <div class="mt-8 text-center text-gray-500">
            <p>Already have an account? <a href="{{ route('login') }}"
                    class="text-amber-600 font-bold hover:underline">Login</a>
            </p>
        </div>

    </div>

</body>

</html>
