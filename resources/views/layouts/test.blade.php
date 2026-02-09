<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TPT-H ERP | Test</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div>
        <h1>Layout Simplifié</h1>
        @yield('content')
    </div>
</body>
</html>