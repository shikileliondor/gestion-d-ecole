<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Relevé de notes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        h2 { font-size: 14px; margin-top: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Relevé de notes - {{ $student->nom }} {{ $student->prenoms }}</h1>

    @foreach ($reportData as $entry)
        <h2>Classe : {{ $entry['class']->nom }}</h2>
        <p>Moyenne générale : {{ $entry['average'] ?? '—' }}</p>
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
