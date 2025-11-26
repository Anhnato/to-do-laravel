<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Vibrant Todo App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-gradient-to-br from-purple-500 via-indigo-500 to-blue-500 min-h-screen flex items-center justify-center">

    <div class="bg-white/30 backdrop-blur-xl rounded-3xl shadow-2xl p-10 max-w-md w-full border border-white/20">

        <h1 class="text-3xl font-extrabold text-white mb-8 text-center drop-shadow-lg">
            ✨ Register
        </h1>

        <form class="space-y-5">
            <div class="flex flex-col">
                <label class="text-white/90 mb-1 font-medium">Name</label>
                <input type="text" placeholder="Your Name"
                    class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-white/90 mb-1 font-medium">Email</label>
                <input type="email" placeholder="you@example.com"
                    class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-white/90 mb-1 font-medium">Password</label>
                <input type="password" placeholder="••••••••"
                    class="p-3 rounded-xl bg-white/60 focus:bg-white focus:ring-2 ring-purple-400 outline-none">
            </div>

            <button type="submit"
                class="w-full bg-purple-600 text-white py-3 rounded-xl font-semibold hover:bg-purple-700 shadow-lg transition">
                Register
            </button>
        </form>

        <p class="text-white/80 text-center mt-6">
            Already have an account
            <a href="/login" class="font-bold text-white hover:text-purple-200">Login</a>
        </p>

    </div>

</body>

</html>