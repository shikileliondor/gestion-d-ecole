<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bulletin {{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Bulletin - {{ $class->nom }} ({{ $period->libelle }})</h1>
    <p>Élève : {{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</p>
    <p>Rang : {{ $rank }} | Moyenne générale : {{ $entry['average'] ?? '—' }}</p>

    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th>Coefficient</th>
                <th>Moyenne</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entry['subjects'] as $subject)
                <tr>
                    <td>{{ $subject['subject'] }}</td>
                    <td>{{ $subject['coefficient'] ?? '—' }}</td>
                    <td>{{ $subject['average'] ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
