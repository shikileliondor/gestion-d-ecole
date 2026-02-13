<x-page-shell title="Détail conversation" :subtitle="$conversation->subject">
    @foreach($conversation->messages as $message)
        <section class="rounded-2xl border border-slate-200 bg-white p-6 mb-4"><h3 class="font-semibold">{{ $message->subject }}</h3><p class="text-xs text-slate-500">{{ $message->sender?->name }} · {{ $message->created_at?->format('d/m/Y H:i') }}</p><p class="mt-3 whitespace-pre-line text-sm">{{ $message->content }}</p><p class="mt-2 text-xs text-slate-500">Destinataires: {{ $message->recipients->pluck('recipient_name')->join(', ') }}</p></section>
    @endforeach
</x-page-shell>
