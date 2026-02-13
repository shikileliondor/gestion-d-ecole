<?php

namespace App\Http\Controllers;

use App\Models\RhDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RhDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = (string) $request->query('category', '');
        $type = (string) $request->query('type', '');
        $sort = (string) $request->query('sort', 'date_desc');

        $documentsQuery = RhDocument::query()->with('user');

        if ($search !== '') {
            $documentsQuery->where(function ($query) use ($search): void {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $documentsQuery->where('category', $category);
        }

        if ($type !== '') {
            $documentsQuery->where('mime_type', 'like', "{$type}/%");
        }

        match ($sort) {
            'date_asc' => $documentsQuery->oldest(),
            'category_asc' => $documentsQuery->orderBy('category')->latest(),
            'type_asc' => $documentsQuery->orderBy('mime_type')->latest(),
            default => $documentsQuery->latest(),
        };

        return view('rh.documents.index', [
            'documents' => $documentsQuery->paginate(12)->withQueryString(),
            'categories' => RhDocument::CATEGORIES,
            'filters' => [
                'search' => $search,
                'category' => $category,
                'type' => $type,
                'sort' => $sort,
            ],
            'authorizedDocumentIds' => RhDocument::query()
                ->where('user_id', $request->user()->id)
                ->pluck('id')
                ->all(),
            'types' => [
                'application' => 'Documents (PDF/Word/Excel)',
                'image' => 'Images',
                'text' => 'Texte',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', Rule::in(RhDocument::CATEGORIES)],
            'is_urgent' => ['nullable', 'boolean'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,csv,png,jpeg,jpg,webp,gif,txt', 'max:12288'],
        ]);

        $file = $request->file('file');
        $path = $file->store('documents/rh', 'public');

        RhDocument::query()->create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'],
            'is_urgent' => (bool) ($data['is_urgent'] ?? false),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
        ]);

        return back()->with('status', 'Document ajouté avec succès.');
    }

    public function update(Request $request, RhDocument $document): RedirectResponse
    {
        abort_unless($this->canManage($request, $document), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', Rule::in(RhDocument::CATEGORIES)],
            'is_urgent' => ['nullable', 'boolean'],
        ]);

        $document->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'],
            'is_urgent' => (bool) ($data['is_urgent'] ?? false),
        ]);

        return back()->with('status', 'Informations du document mises à jour.');
    }

    public function destroy(Request $request, RhDocument $document): RedirectResponse
    {
        abort_unless($this->canManage($request, $document), 403);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('status', 'Document supprimé avec succès.');
    }

    public function download(RhDocument $document)
    {
        abort_unless(Storage::disk('public')->exists($document->file_path), 404);

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    private function canManage(Request $request, RhDocument $document): bool
    {
        return (int) $document->user_id === (int) $request->user()->id;
    }
}
