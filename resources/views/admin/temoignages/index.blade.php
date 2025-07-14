@extends('layouts.admin')

@section('title', '💬 Témoignages')

@section('content')
<div class="container">
    <h1 class="text-white mt-5">temoignages effectuer</h1>
</div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($testimonials->isEmpty())
                    <p>Aucun témoignage disponible pour le moment.</p>
                @else

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contenu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Soumis le</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approuvé le</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($testimonials as $testimonial)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $testimonial->name }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($testimonial->content, 50) }}</td>
                                    <td class="px-6 py-4">{{ $testimonial->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        {{ $testimonial->updated_at  ? $testimonial->updated_at ->format('d/m/Y H:i') : '—' }}
                                    </td>

                                    <td class="px-6 py-4">{{ $testimonial->approved ? 'Approuvé' : 'En attente' }}</td>
                                    <td class="px-6 py-4">
                                        @if (!$testimonial->approved)
                                            <form action="{{ route('admin.testimonials.approve', $testimonial->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success hover:text-green-800">Approuver</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger hover:text-red-800" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        @endsection
