<!DOCTYPE html>
<html>
<head>
    <title>Debug - Estatísticas</title>
    <style>
        pre { background: #f4f4f4; padding: 10px; }
        table { border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <h1>Debug Mode</h1>
    
    <!-- Debug 1: Verifica se a view está sendo carregada -->
    <p>View carregada com sucesso em: {{ now() }}</p>

    <!-- Debug 2: Mostra dados recebidos -->
    <h2>Dados Recebidos:</h2>
    <pre>{{ print_r($stats, true) }}</pre>

    <!-- Debug 3: Mostra dados brutos do banco -->
    <h2>Dados Brutos ({{ $rawData->count() }} registros):</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Dieta</th>
            <th>Pulso</th>
        </tr>
        @foreach($rawData as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->kind }}</td>
            <td>{{ $item->diet }}</td>
            <td>{{ $item->pulse }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>