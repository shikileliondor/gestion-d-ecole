<x-page-shell title="Outils · Messagerie" subtitle="Conversations, envoyés, brouillons, non lus.">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex gap-2">@foreach(['conversations'=>'Conversations','sent'=>'Messages envoyés','drafts'=>'Brouillons','unread'=>'Messages non lus'] as $value=>$label)<a href="{{ route('tools.messaging.index',['tab'=>$value]) }}" class="rounded-xl px-4 py-2 text-sm {{ $tab===$value?'bg-blue-600 text-white':'bg-white border border-slate-200 text-slate-700' }}">{{ $label }}</a>@endforeach</div>
        <a href="{{ route('tools.messaging.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Nouveau message</a>
    </div>
    @if($tab==='conversations')
        @foreach($conversations as $conversation)
            <a href="{{ route('tools.messaging.show',$conversation) }}" class="mt-3 block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h3 class="text-lg font-semibold">{{ $conversation->subject }}</h3><p class="text-sm text-slate-500">{{ $conversation->creator?->name }} · {{ $conversation->created_at?->format('d/m/Y H:i') }}</p></a>
        @endforeach
    @elseif($tab==='sent')
        @foreach($sentMessages as $message)<div class="mt-3 rounded-2xl border border-slate-200 bg-white p-5">{{ $message->subject }} · {{ $message->recipients->count() }} destinataire(s)</div>@endforeach
        {{ $sentMessages->links() }}
    @elseif($tab==='drafts')
        @foreach($drafts as $message)<div class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 p-5">{{ $message->subject }}</div>@endforeach
        {{ $drafts->links() }}
    @else
        @foreach($unread as $message)<div class="mt-3 rounded-2xl border border-indigo-200 bg-indigo-50 p-5">{{ $message->subject }}</div>@endforeach
        {{ $unread->links() }}
    @endif
</x-page-shell>
