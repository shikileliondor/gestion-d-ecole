<x-page-shell
    title="Comptabilité · Paramètres · Modèles reçus / factures"
    subtitle="Configurez l'identité visuelle et les informations globales de l'établissement."
>
    @php
        $schoolLogoUrl = $schoolSettings?->logo_path ? Storage::disk('public')->url($schoolSettings->logo_path) : null;
        $receiptLogoUrl = $schoolSettings?->cachet_path ? Storage::disk('public')->url($schoolSettings->cachet_path) : null;
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-6">
        <form class="space-y-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 sm:p-8">
            <section class="space-y-5">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Informations générales</h3>
                    <p class="mt-1 text-sm text-slate-500">Renseignez les informations principales affichées sur le portail scolaire.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block space-y-2 text-sm font-medium text-slate-700">
                        <span>Nom de l’établissement <span class="text-rose-500">*</span></span>
                        <input
                            type="text"
                            name="school_name"
                            required
                            placeholder="Ex: Groupe Scolaire Horizon"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        />
                    </label>

                    <label class="block space-y-2 text-sm font-medium text-slate-700">
                        <span>Devise <span class="text-rose-500">*</span></span>
                        <select
                            name="currency"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >
                            <option value="XOF" selected>Franc CFA (XOF)</option>
                            <option value="EUR">Euro (EUR)</option>
                            <option value="USD">Dollar US (USD)</option>
                        </select>
                    </label>

                    <label class="block space-y-2 text-sm font-medium text-slate-700">
                        <span>Email officiel <span class="text-rose-500">*</span></span>
                        <input
                            type="email"
                            name="official_email"
                            required
                            placeholder="contact@ecole.ci"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        />
                    </label>

                    <label class="block space-y-2 text-sm font-medium text-slate-700">
                        <span>Téléphone <span class="text-rose-500">*</span></span>
                        <input
                            type="tel"
                            name="official_phone"
                            required
                            placeholder="+225 07 00 00 00 00"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        />
                    </label>

                    <label class="block space-y-2 text-sm font-medium text-slate-700">
                        <span>Taux de frais (%)</span>
                        <input
                            type="number"
                            name="fee_rate"
                            step="0.01"
                            min="0"
                            placeholder="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        />
                    </label>

                    <label class="block space-y-2 text-sm font-medium text-slate-700 md:col-span-2">
                        <span>Adresse complète <span class="text-rose-500">*</span></span>
                        <textarea
                            name="full_address"
                            rows="3"
                            required
                            placeholder="Ex: Cocody, Riviera 3, Rue des Jardins"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        ></textarea>
                    </label>

                    <label class="block space-y-2 text-sm font-medium text-slate-700 md:col-span-2">
                        <span>URL du site public</span>
                        <input
                            type="url"
                            name="public_url"
                            placeholder="https://www.ecole.ci"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        />
                    </label>
                </div>
            </section>

            <section class="space-y-4 border-t border-slate-200 pt-8">
                <h3 class="text-lg font-semibold text-slate-900">Logo de l’établissement</h3>

                <div class="space-y-3">
                    <p class="text-sm font-medium text-slate-700">Logo actuel</p>
                    <div class="h-24 w-24 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                        @if ($schoolLogoUrl)
                            <img src="{{ $schoolLogoUrl }}" alt="Logo établissement actuel" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-xs font-medium text-slate-400">Aucun logo</div>
                        @endif
                    </div>
                </div>

                <label for="school-logo" class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center transition hover:border-indigo-300 hover:bg-indigo-50/40">
                    <span class="text-sm font-semibold text-indigo-600">Glissez-déposez un fichier ou cliquez pour uploader</span>
                    <span class="text-xs text-slate-500">Formats acceptés : PNG, JPG, SVG (max 2MB)</span>
                    <input id="school-logo" type="file" name="school_logo" accept=".png,.jpg,.jpeg,.svg" class="sr-only" />
                </label>

                <p class="text-xs text-slate-500">Le logo principal sera utilisé dans l’interface et les documents administratifs.</p>
            </section>

            <section class="space-y-4 border-t border-slate-200 pt-8">
                <h3 class="text-lg font-semibold text-slate-900">Logo des reçus de paiement</h3>
                <p class="text-sm text-slate-500">Utilisé sur PDF et email.</p>

                <div class="space-y-3">
                    <p class="text-sm font-medium text-slate-700">Aperçu du logo actuel</p>
                    <div class="h-24 w-24 overflow-hidden rounded-xl border border-amber-200 bg-amber-50">
                        @if ($receiptLogoUrl)
                            <img src="{{ $receiptLogoUrl }}" alt="Logo reçu actuel" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-xs font-medium text-slate-400">Aucun logo</div>
                        @endif
                    </div>
                </div>

                <label for="receipt-logo" class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-amber-300 bg-amber-50/40 px-4 py-8 text-center transition hover:border-indigo-300 hover:bg-indigo-50/40">
                    <span class="text-sm font-semibold text-amber-600">Uploader le logo des reçus</span>
                    <span class="text-xs text-slate-500">PNG, JPG, SVG (max 2MB)</span>
                    <input id="receipt-logo" type="file" name="receipt_logo" accept=".png,.jpg,.jpeg,.svg" class="sr-only" />
                </label>
            </section>

            <section class="space-y-4 border-t border-slate-200 pt-8">
                <h3 class="text-lg font-semibold text-slate-900">Mode maintenance</h3>
                <label class="flex items-start justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div>
                        <p class="text-sm font-medium text-slate-800">Activer le mode maintenance</p>
                        <p class="mt-1 text-sm text-slate-500">Activer pour bloquer l’accès public au portail scolaire</p>
                    </div>
                    <span class="relative inline-flex h-7 w-12 shrink-0 items-center">
                        <input type="checkbox" name="maintenance_mode" class="peer sr-only" />
                        <span class="absolute inset-0 rounded-full bg-slate-300 transition peer-checked:bg-indigo-600"></span>
                        <span class="absolute left-1 h-5 w-5 rounded-full bg-white shadow-sm transition peer-checked:translate-x-5"></span>
                    </span>
                </label>
            </section>

            <div class="flex justify-end border-t border-slate-200 pt-6">
                <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                    Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
</x-page-shell>
