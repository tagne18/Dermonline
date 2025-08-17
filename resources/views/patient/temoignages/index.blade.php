<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Témoignages') }}
        </h2>
    </x-slot>

    @foreach(auth()->user()->notifications as $notification)
    @if(isset($notification->data['message']))
        <div class="alert alert-info mb-2">
            {{ $notification->data['message'] }}
            <br>
            <small>{{ $notification->created_at->format('d/m/Y H:i') }}</small>
        </div>
    @endif
@endforeach

    <div class="py-12">
        <div class="max-w-7xl mx-auto ">
            <div class="mb-4 btn btn-success">
                <a href="{{ route('patient.temoignages.create') }}" class="btn btn-success hover:bg-blue-700  font-bold py-2 px-4 rounded">
                    Ajouter un témoignage
                </a>
            </div>
            <div class="overflow-hidden  sm:rounded-lg p-4">


                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($testimonials->isEmpty())
                    <p>Aucun témoignage disponible pour le moment.</p>
                @else
                    <div class="grid gap-4">
                        @foreach ($testimonials as $testimonial)
                            <div class="border p-4 rounded bg-white shadow">
                                @if($testimonial->image)
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Photo" class="w-16 h-16 rounded-full mb-2">
                                @endif
                                <p class="italic text-gray-700 mb-2">"{{ $testimonial->content }}"</p>
                                <p class="font-bold">{{ $testimonial->name }}</p>
                                @if ($testimonial->proffession)
                                    <p class="text-sm text-gray-500">{{ $testimonial->occupation }}</p>
                                @endif
                                <p class="text-xs text-gray-600 mt-2">
                                    Statut : <span class="{{ $testimonial->approved ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $testimonial->approved ? 'Approuvé' : 'En attente' }}
                                    </span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
