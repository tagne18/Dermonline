<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if($messages->isEmpty())
                    <div class="p-4 text-center text-muted">Aucun message disponible</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($messages as $message)
                            <li class="list-group-item">
                                <div class="fw-bold mb-1">
                                    De : {{ $message->sender->name ?? 'Médecin inconnu' }}
                                    <span class="text-muted float-end" style="font-size:0.9em;">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-1">{!! nl2br(e($message->content)) !!}</div>
                                @if($message->attachment)
                                    <div>
                                        <a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">Télécharger la pièce jointe</a>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
