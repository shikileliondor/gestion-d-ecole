<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Matières (Pédagogie)</h2>
                <p class="text-sm text-gray-500">Activez ou désactivez rapidement les matières utilisées dans les classes.</p>
            </div>
            <a href="{{ route('settings.index') }}" class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Gérer dans Paramètres
            </a>
        </div>
    </x-slot>

    <div class="py-6" data-async-page>
        <div class="mx-auto max-w-7xl space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" data-filter-form class="flex flex-wrap items-end gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Recherche</label>
                        <input name="q" value="{{ $search }}" type="text" placeholder="Mathématiques, Français..." class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Statut</label>
                        <select name="status" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Tous</option>
                            <option value="active" @selected($status === 'active')>Actives</option>
                            <option value="inactive" @selected($status === 'inactive')>Inactives</option>
                        </select>
                    </div>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Filtrer</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-800">Liste des matières</h3>
                    <p class="text-sm text-slate-500">L'édition détaillée (coefficients officiels) reste dans Paramètres.</p>
                </div>
                <div class="px-5 py-4">
                    <div class="alert success mb-4 hidden" data-feedback-success></div>
                    <div class="alert error mb-4 hidden" data-feedback-error></div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                    <th class="px-4 py-3">Matière</th>
                                    <th class="px-4 py-3">Code</th>
                                    <th class="px-4 py-3">Statut</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody data-async-target>
                                @forelse ($subjects as $subject)
                                    @include('pedagogy.partials.subject-row', ['subject' => $subject])
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">Aucune matière trouvée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pedagogy/async-actions.js') }}" defer></script>
</x-app-layout>
