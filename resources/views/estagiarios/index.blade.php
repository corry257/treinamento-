<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagiários</title>
</head>
<body>
    <ul>
    @foreach($estagiarios as $estagiario)
        <li>{{ $estagiario->nome }} - {{ $estagiario->email }} - {{ $estagiario->idade }} anos</li>
    @endforeach
    </ul>
</body>
</html>