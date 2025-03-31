<!DOCTYPE html>
<html>
<head>
    <title>Estatísticas de Exercícios</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Estatísticas de Exercícios</h1>
    
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(empty($stats))
        <p>Nenhuma estatística disponível</p>
    @else
        <table>
            <tr>
                <th>Tipo de Exercício</th>
                <th>Quantidade</th>
                <th>Pulso Médio</th>
            </tr>
            @foreach($stats as $kind => $data)
            <tr>
                <td>{{ ucfirst($kind) }}</td>
                <td>{{ $data['count'] }}</td>
                <td>{{ $data['avg_pulse'] }}</td>
            </tr>
            @endforeach
        </table>
    @endif

    <p><a href="/exercises/import">Importar Novamente</a></p>
</body>
</html>