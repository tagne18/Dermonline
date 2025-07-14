@extends('layouts.medecin')

@section('title', 'Mon Profil')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">⚙️ Mon Profil</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('medecin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-3 text-center">
                <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default.jpeg') }}" alt="Photo de profil" class="img-thumbnail mb-2" style="max-width: 150px;">
                <div class="mb-2">
                    <label for="profile_photo" class="form-label">Changer la photo</label>
                    <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                </div>
            </div>
            <div class="col-md-9">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sexe</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->gender }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Âge</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->age : '' }}" disabled>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="specialite" class="form-label">Spécialité</label>
                        <input type="text" class="form-control" id="specialite" name="specialite" value="{{ old('specialite', Auth::user()->specialite) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville', Auth::user()->ville) }}">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="a_propos" class="form-label">À propos de moi</label>
                    <textarea class="form-control" id="a_propos" name="a_propos" rows="3">{{ old('a_propos', Auth::user()->a_propos) }}</textarea>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
