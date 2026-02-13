<x-page-shell title="Outils · Nouveau message" subtitle="Messagerie interne + envoi email parent avec suivi.">
    <form method="POST" action="{{ route('tools.messaging.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-semibold text-slate-900">1) Destinataire</h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Type de destinataire *</label>
                    <select name="recipient_type" class="mt-1 w-full rounded-xl border-slate-300" required>
                        @foreach($recipientTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('recipient_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Destinataires (multi) *</label>
                    <select name="recipient_ids[]" multiple class="mt-1 w-full rounded-xl border-slate-300 min-h-36" required>
                        @foreach(['parents' => 'Parents', 'classes' => 'Classes', 'niveaux' => 'Niveaux', 'groupes' => 'Groupes', 'profils' => 'Profils'] as $key => $label)
                            <optgroup label="{{ $label }}">
                                @foreach($recipientOptions[$key] as $option)
                                    <option value="{{ $option['id'] }}">{{ $option['label'] }} @if(isset($option['meta'])) — {{ $option['meta'] }} @endif</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-semibold text-slate-900">2) Contenu du message</h3>
            <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full rounded-xl border-slate-300" placeholder="Objet *">
            <div class="grid gap-4 md:grid-cols-2">
                <select name="message_type" class="rounded-xl border-slate-300" required>
                    <option value="information">Information</option><option value="important">Important</option><option value="urgence">Urgence</option><option value="rappel">Rappel</option>
                </select>
                <select name="missing_email_strategy" class="rounded-xl border-slate-300"><option value="ignorer">Ignorer email manquant</option><option value="bloquer">Bloquer si email manquant</option></select>
            </div>
            <textarea name="content" rows="6" class="w-full rounded-xl border-slate-300" placeholder="Contenu *" required>{{ old('content') }}</textarea>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-semibold text-slate-900">3) Options avancées</h3>
            <input type="file" name="attachments[]" multiple class="w-full rounded-xl border-slate-300">
            <div class="grid gap-2 sm:grid-cols-2 text-sm text-slate-700">
                <label><input type="checkbox" name="internal_channel" value="1" checked> Messagerie interne</label>
                <label><input type="checkbox" name="email_channel" value="1"> Email parent</label>
                <label><input type="checkbox" name="requires_read_receipt" value="1"> Accusé de lecture</label>
                <label><input type="checkbox" name="save_as_draft" value="1"> Brouillon</label>
            </div>
        </section>

        <div class="flex justify-end gap-3"><a href="{{ route('tools.messaging.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm">Retour</a><button class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white">Envoyer le message</button></div>
    </form>
</x-page-shell>
