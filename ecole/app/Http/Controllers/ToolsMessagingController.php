<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\EleveTuteur;
use App\Models\Inscription;
use App\Models\Message;
use App\Models\MessageConversation;
use App\Models\Niveau;
use App\Models\Serie;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ToolsMessagingController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->string('tab', 'conversations')->toString();

        $conversations = MessageConversation::query()
            ->with(['creator', 'messages' => fn ($query) => $query->latest()->withCount('recipients')])
            ->latest()
            ->take(20)
            ->get();

        $sentMessages = Message::query()
            ->with(['recipients', 'attachments'])
            ->where('sender_id', Auth::id())
            ->where('is_draft', false)
            ->latest()
            ->paginate(10, ['*'], 'sent_page');

        $drafts = Message::query()
            ->where('sender_id', Auth::id())
            ->where('is_draft', true)
            ->latest()
            ->paginate(10, ['*'], 'draft_page');

        $unread = Message::query()
            ->where('is_draft', false)
            ->whereHas('recipients', fn ($query) => $query->where('internal_status', 'non_lu'))
            ->latest()
            ->paginate(10, ['*'], 'unread_page');

        return view('tools.messaging.index', compact('tab', 'conversations', 'sentMessages', 'drafts', 'unread'));
    }

    public function create(): View
    {
        return view('tools.messaging.create', [
            'recipientTypes' => [
                'parent_single' => 'Parent (unitaire)',
                'parent_multi' => 'Parents (multi)',
                'classe' => 'Classe',
                'niveau' => 'Série/Niveau',
                'groupe' => 'Groupe',
                'profil' => 'Profil',
            ],
            'recipientOptions' => $this->recipientOptions(),
        ]);
    }

    public function store(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_type' => ['required', 'string'],
            'recipient_ids' => ['required', 'array', 'min:1'],
            'recipient_ids.*' => ['required', 'string'],
            'subject' => ['required', 'string', 'max:255'],
            'message_type' => ['required', 'in:information,important,urgence,rappel'],
            'content' => ['required', 'string'],
            'internal_channel' => ['nullable', 'boolean'],
            'email_channel' => ['nullable', 'boolean'],
            'requires_read_receipt' => ['nullable', 'boolean'],
            'missing_email_strategy' => ['required', 'in:ignorer,bloquer'],
            'attachments.*' => ['nullable', 'file', 'max:5120'],
            'save_as_draft' => ['nullable', 'boolean'],
        ]);

        $recipients = $this->resolveRecipients($validated['recipient_type'], $validated['recipient_ids']);

        if ($recipients->isEmpty()) {
            return back()->withErrors(['recipient_ids' => 'Aucun destinataire valide trouvé.'])->withInput();
        }

        $missingEmailRecipients = $recipients->filter(fn (array $recipient) => empty($recipient['email']));
        $emailRequested = (bool) ($validated['email_channel'] ?? false);

        if ($emailRequested && $validated['missing_email_strategy'] === 'bloquer' && $missingEmailRecipients->isNotEmpty()) {
            return back()
                ->withErrors(['email_channel' => 'Certains parents n\'ont pas d\'email. Mettez les profils à jour ou choisissez ignorer.'])
                ->withInput();
        }

        $isDraft = (bool) ($validated['save_as_draft'] ?? false);

        $message = DB::transaction(function () use ($validated, $recipients, $request, $emailRequested, $isDraft) {
            $conversation = MessageConversation::query()->create([
                'subject' => $validated['subject'],
                'created_by' => Auth::id(),
            ]);

            $message = Message::query()->create([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'subject' => $validated['subject'],
                'message_type' => $validated['message_type'],
                'content' => $validated['content'],
                'is_draft' => $isDraft,
                'internal_channel' => (bool) ($validated['internal_channel'] ?? true),
                'email_channel' => $emailRequested,
                'requires_read_receipt' => (bool) ($validated['requires_read_receipt'] ?? false),
                'sent_at' => $isDraft ? null : now(),
            ]);

            foreach ($recipients as $recipient) {
                $message->recipients()->create([
                    'recipient_type' => $recipient['type'],
                    'recipient_id' => $recipient['id'],
                    'recipient_name' => $recipient['name'],
                    'recipient_email' => $recipient['email'],
                    'email_status' => $emailRequested
                        ? ($recipient['email'] ? 'en_attente' : 'email_manquant')
                        : 'non_demande',
                ]);
            }

            foreach ($request->file('attachments', []) as $attachment) {
                $path = $attachment->store('messaging-attachments', 'public');

                $message->attachments()->create([
                    'path' => $path,
                    'original_name' => $attachment->getClientOriginalName(),
                    'mime_type' => $attachment->getClientMimeType(),
                    'size' => $attachment->getSize(),
                ]);
            }

            return $message->load('recipients');
        });

        if ($emailRequested && ! $isDraft) {
            foreach ($message->recipients as $recipient) {
                if (! $recipient->recipient_email) {
                    continue;
                }

                try {
                    Mail::raw($message->content, function ($mail) use ($message, $recipient) {
                        $mail->to($recipient->recipient_email, $recipient->recipient_name)
                            ->subject($message->subject);
                    });

                    $recipient->update([
                        'email_status' => 'envoye',
                        'email_error' => null,
                    ]);
                } catch (\Throwable $throwable) {
                    $recipient->update([
                        'email_status' => 'echec',
                        'email_error' => Str::limit($throwable->getMessage(), 300),
                    ]);
                }
            }
        }

        $auditLogger->log(
            Auth::id(),
            $isDraft ? 'MESSAGERIE_BROUILLON' : 'MESSAGERIE_ENVOI',
            'OUTILS_MESSAGERIE',
            $message->id,
            'SUCCES',
            'Message créé depuis le module Outils > Messagerie.',
            $request,
            [
                'destinataires' => $message->recipients->count(),
                'canal_interne' => $message->internal_channel,
                'canal_email' => $message->email_channel,
            ]
        );

        return redirect()->route('tools.messaging.index')
            ->with('status', $isDraft ? 'Brouillon enregistré.' : 'Message envoyé avec succès.');
    }

    public function show(MessageConversation $conversation): View
    {
        $conversation->load(['creator', 'messages.sender', 'messages.recipients', 'messages.attachments']);

        return view('tools.messaging.show', compact('conversation'));
    }

    private function recipientOptions(): array
    {
        $parents = EleveTuteur::query()
            ->orderBy('nom')
            ->limit(200)
            ->get(['id', 'nom', 'prenoms', 'email']);

        $classes = Classe::query()->orderBy('nom')->get(['id', 'nom']);
        $niveaux = Niveau::query()->orderBy('code')->get(['id', 'code']);
        $series = Serie::query()->orderBy('code')->get(['id', 'code']);

        return [
            'parents' => $parents->map(fn (EleveTuteur $parent) => [
                'id' => (string) $parent->id,
                'label' => trim($parent->prenoms . ' ' . $parent->nom),
                'meta' => $parent->email ?: 'email manquant',
            ])->all(),
            'classes' => $classes->map(fn (Classe $classe) => ['id' => (string) $classe->id, 'label' => $classe->nom])->all(),
            'niveaux' => $niveaux->map(fn (Niveau $niveau) => ['id' => 'niveau:' . $niveau->id, 'label' => $niveau->code])->all(),
            'series' => $series->map(fn (Serie $serie) => ['id' => 'serie:' . $serie->id, 'label' => $serie->libelle])->all(),
            'profils' => [
                ['id' => 'enseignants', 'label' => 'Tous les enseignants'],
                ['id' => 'administratifs', 'label' => 'Personnel administratif'],
                ['id' => 'admins', 'label' => 'Administrateurs / responsables'],
            ],
            'groupes' => [
                ['id' => 'classe:terminale-a', 'label' => 'Terminale A'],
                ['id' => 'niveau:3e', 'label' => 'Parents de 3e'],
            ],
        ];
    }

    private function resolveRecipients(string $recipientType, array $recipientIds)
    {
        return (match ($recipientType) {
            'parent_single', 'parent_multi' => EleveTuteur::query()
                ->whereIn('id', $recipientIds)
                ->get()
                ->map(fn (EleveTuteur $parent) => [
                    'type' => 'parent',
                    'id' => $parent->id,
                    'name' => trim($parent->prenoms . ' ' . $parent->nom),
                    'email' => $parent->email,
                ]),
            'classe' => EleveTuteur::query()
                ->whereIn('eleve_id', Inscription::query()->whereIn('classe_id', $recipientIds)->pluck('eleve_id'))
                ->get()
                ->map(fn (EleveTuteur $parent) => [
                    'type' => 'parent',
                    'id' => $parent->id,
                    'name' => trim($parent->prenoms . ' ' . $parent->nom),
                    'email' => $parent->email,
                ]),
            'niveau' => EleveTuteur::query()
                ->whereIn('eleve_id', Inscription::query()
                    ->whereIn('classe_id', Classe::query()->whereIn('niveau_id', collect($recipientIds)
                        ->map(fn ($id) => (int) str_replace('niveau:', '', $id))
                    )->pluck('id'))
                    ->pluck('eleve_id'))
                ->get()
                ->map(fn (EleveTuteur $parent) => [
                    'type' => 'parent',
                    'id' => $parent->id,
                    'name' => trim($parent->prenoms . ' ' . $parent->nom),
                    'email' => $parent->email,
                ]),

            'groupe' => collect($recipientIds)->flatMap(function ($group) {
                if (str_starts_with($group, 'classe:')) {
                    $classe = Classe::query()->whereRaw('LOWER(nom) = ?', [strtolower(str_replace('classe:', '', $group))])->first();
                    if (! $classe) {
                        return [];
                    }

                    return $this->resolveRecipients('classe', [(string) $classe->id]);
                }

                if (str_starts_with($group, 'niveau:')) {
                    $niveau = Niveau::query()->whereRaw('LOWER(code) = ?', [strtolower(str_replace('niveau:', '', $group))])->first();
                    if (! $niveau) {
                        return [];
                    }

                    return $this->resolveRecipients('niveau', ['niveau:' . $niveau->id]);
                }

                return [];
            }),
            'profil' => User::query()->get()->map(fn (User $user) => [
                'type' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]),
            default => collect(),
        })->unique('id')->values();
    }
}
