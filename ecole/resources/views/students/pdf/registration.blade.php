<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Fiche d'inscription</title>
        <style>
            * { font-family: DejaVu Sans, sans-serif; }
            body { font-size: 12px; color: #111827; }
            .header { text-align: center; margin-bottom: 12px; }
            .title { font-size: 18px; font-weight: bold; margin-top: 4px; }
            .sub-title { font-size: 12px; margin-top: 2px; }
            .section { margin-top: 14px; }
            .section h3 { margin: 0 0 6px; font-size: 13px; text-transform: uppercase; border-bottom: 1px solid #111827; padding-bottom: 2px; }
            .grid { width: 100%; border-collapse: collapse; }
            .grid td { padding: 4px 6px; vertical-align: top; }
            .label { font-weight: bold; width: 32%; }
            .value { border-bottom: 1px dotted #6b7280; }
            .checkbox { display: inline-block; width: 10px; height: 10px; border: 1px solid #111827; margin-right: 4px; vertical-align: middle; }
            .checkbox.checked { background: #111827; }
            .photo-box { width: 110px; height: 130px; border: 1px solid #111827; text-align: center; }
            .photo-box img { width: 110px; height: 130px; object-fit: cover; }
            .docs-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
            .docs-table th, .docs-table td { border: 1px solid #111827; padding: 4px; font-size: 11px; }
            .signatures { width: 100%; margin-top: 14px; }
            .signatures td { text-align: center; padding-top: 30px; }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="title">FICHE D'INSCRIPTION</div>
            <div class="sub-title">Année scolaire : {{ $inscription->annee_scolaire ?? '—' }}</div>
            <div class="sub-title">
                Inscription / Réinscription :
                <span class="checkbox {{ $isReEnrollment ? 'checked' : '' }}"></span> Réinscription
            </div>
        </div>

        <table class="grid">
            <tr>
                <td style="width: 80%;">
                    <div class="section">
                        <h3>Identité de l'élève</h3>
                        <table class="grid">
                            <tr>
                                <td class="label">Nom et prénoms</td>
                                <td class="value">{{ $student->nom }} {{ $student->prenoms }}</td>
                            </tr>
                            <tr>
                                <td class="label">Date et lieu de naissance</td>
                                <td class="value">
                                    {{ optional($student->date_naissance)->format('d/m/Y') ?? '—' }}
                                    {{ $student->lieu_naissance ? ' - ' . $student->lieu_naissance : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Nationalité</td>
                                <td class="value">{{ $student->nationalite ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Matricule école</td>
                                <td class="value">{{ $student->matricule ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Matricule national</td>
                                <td class="value">{{ $student->matricule_national ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 20%; text-align: right;">
                    <div class="photo-box">
                        @if ($photoPath && file_exists($photoPath))
                            <img src="{{ $photoPath }}" alt="Photo élève">
                        @else
                            <div style="padding-top: 55px;">Photo</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="section">
            <h3>Scolarité</h3>
            <table class="grid">
                <tr>
                    <td class="label">Classe suivie</td>
                    <td class="value">{{ $inscription->classe_nom ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Établissement d'origine</td>
                    <td class="value">{{ $student->etablissement_origine ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Date d'arrivée</td>
                    <td class="value">{{ optional($student->date_arrivee)->format('d/m/Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Classe précédente</td>
                    <td class="value">{{ $student->classe_precedente ?? '—' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Famille</h3>
            <table class="grid">
                <tr>
                    <td class="label">Nom et prénoms du père</td>
                    <td class="value">{{ $father?->nom ?? '—' }}</td>
                    <td class="label">Profession</td>
                    <td class="value">{{ $father?->profession ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Domicile</td>
                    <td class="value">{{ $father?->adresse ?? '—' }}</td>
                    <td class="label">Téléphone</td>
                    <td class="value">{{ $father?->telephone_1 ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Nom et prénoms de la mère</td>
                    <td class="value">{{ $mother?->nom ?? '—' }}</td>
                    <td class="label">Profession</td>
                    <td class="value">{{ $mother?->profession ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Domicile</td>
                    <td class="value">{{ $mother?->adresse ?? '—' }}</td>
                    <td class="label">Téléphone</td>
                    <td class="value">{{ $mother?->telephone_1 ?? '—' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Correspondant</h3>
            <table class="grid">
                <tr>
                    <td class="label">Nom et prénoms</td>
                    <td class="value">{{ $guardian?->nom ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Profession</td>
                    <td class="value">{{ $guardian?->profession ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Domicile</td>
                    <td class="value">{{ $guardian?->adresse ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Téléphone</td>
                    <td class="value">{{ $guardian?->telephone_1 ?? '—' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Pièces à fournir à l'inscription</h3>
            <table class="docs-table">
                <thead>
                    <tr>
                        <th>Pièce à présenter</th>
                        <th>Conforme</th>
                        <th>Non conforme</th>
                        <th>Manquant</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Reçu d'inscription en ligne</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Dernier bulletin scolaire</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Extrait d'acte de naissance</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1 paquet de rame et 1 boîte de craie</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Les frais d'examens</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Livret scolaire</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="signatures">
            <tr>
                <td>L'éducateur</td>
                <td>La comptabilité</td>
                <td>Le directeur</td>
            </tr>
        </table>
    </body>
</html>
