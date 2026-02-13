<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800">
            Mon profil
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h3 class="text-base font-semibold text-slate-900">Informations utilisateur</h3>
                <p class="mt-1 text-sm text-slate-500">Résumé de votre compte connecté.</p>

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nom</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $user->name }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $user->email }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Compte créé le</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ optional($user->created_at)->translatedFormat('d F Y à H:i') }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email vérifié</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $user->email_verified_at ? 'Oui' : 'Non' }}</dd>
                    </div>
                </dl>
            </section>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-rose-100 bg-white p-4 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
