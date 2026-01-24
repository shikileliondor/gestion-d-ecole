@php
    $assignments = $class->subjectAssignments ?? collect();
@endphp

@if ($assignments->isEmpty())
    <p class="helper-text">Aucune matière affectée pour le moment.</p>
@else
    @foreach ($assignments as $assignment)
        @php
            $levelParts = [];
            if ($assignment->subject?->level) {
                $levelParts[] = $assignment->subject->level;
            }
            if ($assignment->subject?->series) {
                $levelParts[] = 'Série ' . $assignment->subject->series;
            }
            if ($assignment->coefficient) {
                $levelParts[] = 'Coef. ' . $assignment->coefficient;
            }
            $teachers = $assignment->teachers ?? collect();
        @endphp
        <div class="subject-summary__item">
            <div class="subject-summary__header">
                <div class="subject-summary__name">{{ $assignment->subject?->name ?? 'Matière' }}</div>
                <div class="subject-summary__meta">{{ $levelParts ? implode(' • ', $levelParts) : 'Tous niveaux' }}</div>
            </div>
            <div class="subject-summary__teachers">
                @if ($teachers->isNotEmpty())
                    @foreach ($teachers as $teacher)
                        <span class="teacher-badge">{{ trim($teacher->last_name . ' ' . $teacher->first_name) }}</span>
                    @endforeach
                @else
                    <span class="teacher-badge">Enseignant à définir</span>
                @endif
            </div>
            <div class="color-chip">
                <span class="color-swatch" style="background-color: {{ $assignment->color ?? '#e2e8f0' }}"></span>
                <span>{{ $assignment->color ? 'Couleur ' . $assignment->color : 'Couleur automatique' }}</span>
            </div>
        </div>
    @endforeach
@endif
