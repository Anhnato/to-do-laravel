<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - SunnyDay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-yellow-50 min-h-screen flex items-center justify-center text-gray-800 font-sans">
    <div class="text-center p-10">
        <div class="mb-6 relative">
            <i class="fa-solid fa-sun text-6xl text-amber-500 absolute -top-4 -right-4 animate-pulse"></i>
            <i class="fa-solid fa-cloud text-9xl text-white drop-shadow-xl relative z-10"></i>
        </div>

        <h1 class="text-8xl font-black text-amber-500 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Whoops! It's a bit cloudy here.</h2>
        <p class="text-gray-500 mb-8">We couldn't find the page you were looking for.</p>

        <a href="{{ url('/') }}"
            class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg transition transform hover:scale-105 inline-flex items-center gap-2">
            <i class="fa-solid fa-house"></i> Go Home
        </a>
    </div>
</body>

</html>
