<option value="{{ $subject->id }}">
    {{ $subject->name }}
    @if ($subject->level)
        ({{ $subject->level }}@if ($subject->series) • Série {{ $subject->series }}@endif)
    @elseif ($subject->series)
        (Série {{ $subject->series }})
    @endif
</option>
