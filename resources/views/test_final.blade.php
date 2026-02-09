<!DOCTYPE html>
<html>
<head>
    <title>Test Final</title>
</head>
<body>
    <h1>TEST FINAL - {{ date('Y-m-d H:i:s') }}</h1>
    <p>Si vous voyez ce message, Blade fonctionne correctement.</p>
    <p>Contenu de la section 'content':</p>
    <div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">
        @yield('content')
    </div>
</body>
</html>