<tr data-subject-row-id="{{ $subject->id }}">
    <td class="px-4 py-3 text-sm font-semibold text-slate-900">{{ $subject->nom }}</td>
    <td class="px-4 py-3 text-sm text-slate-500">{{ $subject->code }}</td>
    <td class="px-4 py-3">
        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $subject->actif ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
            {{ $subject->actif ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td class="px-4 py-3 text-right">
        <a href="{{ route('settings.index') }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Gérer dans Paramètres
        </a>
    </td>
</tr>
