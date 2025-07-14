<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un Témoignage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sm:rounded-lg p-6">
                <form method="POST" action="{{ route('patient.temoignages.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- <div class="mb-4">
                        <label for="name" class="block text-gray-700">Nom</label>
                        <input type="text" id="name" name="name" class="w-full border-gray-300 rounded @error('name') border-red-500 @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700">Témoignage</label>
                        <textarea id="content" name="content" class="w-full border-gray-300 rounded @error('content') border-red-500 @enderror" rows="5" required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="occupation" class="block text-gray-700">Profession (optionnel)</label>
                        <input type="text" id="occupation" name="occupation" class="w-full border-gray-300 rounded @error('occupation') border-red-500 @enderror" value="{{ old('occupation', Auth::user()->occupation) }}">
                        @error('occupation')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-gray-700">Photo (optionnel)</label>
                        <input type="file" id="image" name="image" class="w-full border-gray-300 rounded @error('image') border-red-500 @enderror" accept="image/*">
                        @error('image')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success hover:bg-blue-700  font-bold py-2 px-4 rounded">
                        Soumettre
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
