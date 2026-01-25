<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bulletins {{ $class->nom }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Bulletins - {{ $class->nom }} ({{ $period->libelle }})</h1>
    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Élève</th>
                <th>Moyenne générale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</td>
                    <td>{{ $entry['average'] ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
