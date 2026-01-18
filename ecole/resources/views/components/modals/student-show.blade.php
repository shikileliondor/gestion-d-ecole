{{-- Student profile modal component. --}}
<div
    x-show="showStudentModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
    role="dialog"
    aria-modal="true"
>
    <div class="absolute inset-0 bg-gray-900/50" @click="showStudentModal = false"></div>

    <div class="relative w-full max-w-3xl rounded-2xl bg-white p-6 shadow-xl">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-lg font-semibold text-gray-900">
                    Fiche élève -
                    <span x-text="selectedStudent.full_name ?? ''"></span>
                </p>
                <p class="text-sm text-gray-500">Profil complet et informations détaillées</p>
            </div>
            <button
                type="button"
                class="rounded-full p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                @click="showStudentModal = false"
                aria-label="Fermer"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-6">
            <x-tabs
                :tabs="[
                    ['key' => 'informations', 'label' => 'Informations'],
                    ['key' => 'notes', 'label' => 'Notes'],
                    ['key' => 'paiements', 'label' => 'Paiements'],
                    ['key' => 'documents', 'label' => 'Documents'],
                ]"
                active="informations"
            />
        </div>

        <div class="mt-6 space-y-6">
            <div x-show="activeTab === 'informations'">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-1">
                        <p class="text-sm text-gray-400">Matricule</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.admission_number ?? ''"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-400">Date de naissance</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.date_of_birth ?? ''"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-400">Classe</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.class_name ?? ''"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-400">Téléphone élève</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.phone ?? ''"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-400">Parent / Tuteur</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.parent_name ?? ''"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-400">Téléphone parent</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.parent_phone ?? ''"></p>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <p class="text-sm text-gray-400">Adresse</p>
                        <p class="text-base font-semibold text-gray-900" x-text="selectedStudent.address ?? ''"></p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'notes'" class="rounded-xl border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                Notes pédagogiques bientôt disponibles.
            </div>

            <div x-show="activeTab === 'paiements'" class="rounded-xl border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                Historique des paiements à afficher ici.
            </div>

            <div x-show="activeTab === 'documents'" class="rounded-xl border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                Documents scannés à ajouter.
            </div>
        </div>
    </div>
</div>
