<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ubicaciones</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>📍 Reporte de Ubicaciones</h2>
    <p><strong>Tipo:</strong> {{ ucfirst($tipo) }}</p>
    <p><strong>Periodo:</strong> {{ $periodo }}</p>

    <table>
        <thead>
            <tr>
                <th>Visitante</th>
                <th>Celular</th>
                <th>Lugar</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ubicaciones as $u)
            <tr>
                <td>{{ $u->nombre }}</td>
                <td>{{ $u->celular }}</td>
                <td>{{ $u->lugar->nombre ?? 'N/A' }}</td>
                <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
