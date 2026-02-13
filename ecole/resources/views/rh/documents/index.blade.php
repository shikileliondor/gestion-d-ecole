<x-app-layout>
    <div class="space-y-6 py-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">Documents RH & Urgences</h1>
                    <p class="mt-1 text-sm text-slate-500">Espace centralisé pour déposer, organiser et télécharger les documents RH.</p>
                </div>
                <button
                    type="button"
                    onclick="document.getElementById('add-document-form').scrollIntoView({ behavior: 'smooth' })"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
                >
                    + Ajouter un document
                </button>
            </div>

            @if (session('status'))
                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('rh.documents.index') }}" class="mt-6 grid gap-3 lg:grid-cols-4">
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Rechercher un titre, description, fichier..." class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                <select name="category" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item }}" @selected($filters['category'] === $item)>{{ $item }}</option>
                    @endforeach
                </select>
                <select name="type" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les types</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected($filters['type'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="sort" class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="date_desc" @selected($filters['sort'] === 'date_desc')>Date (récent → ancien)</option>
                    <option value="date_asc" @selected($filters['sort'] === 'date_asc')>Date (ancien → récent)</option>
                    <option value="category_asc" @selected($filters['sort'] === 'category_asc')>Catégorie</option>
                    <option value="type_asc" @selected($filters['sort'] === 'type_asc')>Type de document</option>
                </select>
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white lg:col-span-4 lg:justify-self-start">Filtrer</button>
            </form>
        </div>

        <div id="add-document-form" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Ajouter un document</h2>
            <form method="POST" action="{{ route('rh.documents.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-2">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Titre</label>
                    <input type="text" name="title" required value="{{ old('title') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Catégorie</label>
                    <select name="category" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($categories as $item)
                            <option value="{{ $item }}" @selected(old('category') === $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Fichier</label>
                    <input type="file" name="file" required class="w-full rounded-xl border-slate-200 text-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.png,.jpg,.jpeg,.webp,.gif,.txt">
                    <p class="mt-1 text-xs text-slate-500">PDF, Word, Excel, images, texte (max 12 MB).</p>
                </div>
                <label class="inline-flex items-center gap-2 self-end text-sm font-medium text-slate-700">
                    <input type="checkbox" name="is_urgent" value="1" @checked(old('is_urgent')) class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                    Marquer comme urgent
                </label>
                <div class="md:col-span-2">
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Enregistrer le document</button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @if ($documents->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                    <p class="text-base font-semibold text-slate-700">Aucun document ajouté pour le moment.</p>
                    <p class="mt-1 text-sm text-slate-500">Cliquez sur "Ajouter un document" pour constituer votre base RH et urgences.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                                <th class="px-3 py-3">Document</th>
                                <th class="px-3 py-3">Catégorie</th>
                                <th class="px-3 py-3">Auteur</th>
                                <th class="px-3 py-3">Date d'ajout</th>
                                <th class="px-3 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($documents as $document)
                                <tr>
                                    <td class="px-3 py-4 align-top">
                                        <p class="font-semibold text-slate-900">{{ $document->title }}</p>
                                        <p class="text-xs text-slate-500">Fichier : {{ $document->original_name }}</p>
                                        @if ($document->description)
                                            <p class="mt-1 text-xs text-slate-500">{{ $document->description }}</p>
                                        @endif
                                        @if ($document->is_urgent)
                                            <span class="mt-2 inline-flex rounded-full bg-rose-100 px-2 py-1 text-[11px] font-semibold text-rose-700">Urgent</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-slate-700">{{ $document->category }}</td>
                                    <td class="px-3 py-4 text-slate-700">{{ $document->user?->name ?? '—' }}</td>
                                    <td class="px-3 py-4 text-slate-700">{{ $document->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('rh.documents.download', $document) }}" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Télécharger</a>
                                            @if (in_array($document->id, $authorizedDocumentIds, true))
                                                <details>
                                                    <summary class="cursor-pointer rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700">Modifier</summary>
                                                    <form method="POST" action="{{ route('rh.documents.update', $document) }}" class="mt-2 w-72 space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="title" value="{{ $document->title }}" class="w-full rounded-lg border-slate-200 text-xs">
                                                        <textarea name="description" rows="2" class="w-full rounded-lg border-slate-200 text-xs">{{ $document->description }}</textarea>
                                                        <select name="category" class="w-full rounded-lg border-slate-200 text-xs">
                                                            @foreach ($categories as $item)
                                                                <option value="{{ $item }}" @selected($document->category === $item)>{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label class="inline-flex items-center gap-2 text-xs"><input type="checkbox" name="is_urgent" value="1" @checked($document->is_urgent) class="rounded border-slate-300">Urgent</label>
                                                        <button type="submit" class="w-full rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Enregistrer</button>
                                                    </form>
                                                </details>
                                                <form method="POST" action="{{ route('rh.documents.destroy', $document) }}" onsubmit="return confirm('Supprimer ce document ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700">Supprimer</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $documents->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
