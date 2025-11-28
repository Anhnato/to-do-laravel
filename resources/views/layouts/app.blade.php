<!DOCTYPE html>
<html>

<head>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    {{ $slot ?? '' }}
</body>

</html>