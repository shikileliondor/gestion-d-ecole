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
                <th>Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</td>
                    <td>{{ $entry['average'] ?? '—' }}</td>
                    <td>{{ $entry['appreciation'] ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @foreach ($reportData as $index => $entry)
        <h2 style="font-size: 14px; margin-top: 18px;">{{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</h2>
        <p>Rang : {{ $index + 1 }} | Moyenne générale : {{ $entry['average'] ?? '—' }} | Appréciation : {{ $entry['appreciation'] ?? '—' }}</p>
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
    @endforeach
</body>
</html>
