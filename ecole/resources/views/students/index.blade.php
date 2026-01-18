<x-app-layout>
    <div
        class="min-h-screen bg-gray-50"
        x-data="{
            showStudentModal: false,
            activeTab: 'informations',
            selectedStudent: {},
            openStudentModal(student) {
                this.selectedStudent = student;
                this.activeTab = 'informations';
                this.showStudentModal = true;
            }
        }"
    >
        <div class="mx-auto max-w-6xl px-6 py-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Gestion des élèves</h1>
                    <p class="mt-1 text-sm text-gray-500">Liste complète et fiches détaillées</p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <span class="text-lg">+</span>
                    Ajouter un élève
                </button>
            </div>

            <div class="mt-6 rounded-2xl bg-white p-4 shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex flex-1 items-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M5 11a6 6 0 1112 0 6 6 0 01-12 0z" />
                        </svg>
                        <input
                            type="text"
                            placeholder="Rechercher par nom ou matricule..."
                            class="w-full bg-transparent text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none"
                        />
                    </div>
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-gray-600 transition hover:border-gray-300"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-8.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Toutes les classes
                    </button>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Matricule</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Nom</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Classe</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Statut</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-600">Moyenne</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $student['admission_number'] }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $student['full_name'] }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $student['class_name'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $student['status_class'] }}">
                                        {{ $student['status_label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $student['average'] }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-4 text-gray-600">
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-2 hover:text-gray-900"
                                            data-student='@json($student)'
                                            @click="openStudentModal(JSON.parse($event.currentTarget.dataset.student))"
                                        >
                                            <span>👁️</span>
                                            <span class="text-sm font-medium">Voir</span>
                                        </button>
                                        <button type="button" class="text-lg hover:text-gray-900">✏️</button>
                                        <form method="POST" action="{{ route('students.destroy', $student['id']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-lg hover:text-red-600">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>

        <x-modals.student-show />
    </div>
</x-app-layout>
