<x-page-shell
    title="Élèves · Inscriptions"
    subtitle="Accéder rapidement aux inscriptions et réinscriptions."
>
    <div class="flex flex-wrap gap-3">
        <a
            href="{{ route('students.enrollments.create') }}"
            class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500"
        >
            Nouvelle inscription
        </a>
        <a
            href="{{ route('students.re-enrollments') }}"
            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900"
        >
            Réinscriptions
        </a>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2">
        <a
            href="{{ route('students.enrollments.create') }}"
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-blue-200 hover:shadow-md"
        >
            <h3 class="text-base font-semibold text-slate-900">Nouvelle inscription</h3>
            <p class="mt-2 text-sm text-slate-600">
                Enregistrer un nouvel élève et l'inscrire à la classe de l'année active.
            </p>
        </a>
        <a
            href="{{ route('students.re-enrollments') }}"
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow-md"
        >
            <h3 class="text-base font-semibold text-slate-900">Réinscriptions</h3>
            <p class="mt-2 text-sm text-slate-600">
                Renouveler l'inscription d'un élève en tenant compte de son parcours.
            </p>
        </a>
    </div>
</x-page-shell>
