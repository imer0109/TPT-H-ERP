<!DOCTYPE html>
<html>
<head>
    <title>Test Blade Compilation</title>
</head>
<body>
    <h1>Test de compilation Blade</h1>
    <p>Si vous voyez ce texte, Blade fonctionne correctement.</p>
    <p>Heure actuelle : {{ now()->format('Y-m-d H:i:s') }}</p>
    <p>Variable test : {{ $test ?? 'Variable non définie' }}</p>
    
    @if(isset($test))
        <p>La variable test est définie : {{ $test }}</p>
    @else
        <p>La variable test n'est pas définie</p>
    @endif
    
    <ul>
        @for($i = 1; $i <= 3; $i++)
            <li>Élément {{ $i }}</li>
        @endfor
    </ul>
</body>
</html>