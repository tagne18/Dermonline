@extends('layouts.admin')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Mon Profil</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du profil</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Photo de profil</label>
                            <input class="form-control @error('profile_photo') is-invalid @enderror" 
                                   type="file" id="profile_photo" name="profile_photo" accept="image/*">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagePreview" class="mt-2">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                         alt="Photo de profil" class="img-thumbnail mt-2" style="max-width: 200px;">
                                @endif
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Changer le mot de passe</h6>
                                <small class="text-muted">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</small>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" name="new_password">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control" 
                                           id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Photo de profil</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ $user->profile_photo_url }}" 
                             alt="Photo de profil" 
                             class="img-fluid rounded-circle" 
                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4e73df;">
                    </div>
                    <p class="mb-1"><strong>{{ $user->name }}</strong></p>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <p class="small text-muted mb-0">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Membre depuis {{ $user->created_at->format('d/m/Y') }}
                    </p>
                    @if($user->last_login_at)
                        <p class="small text-muted mb-0">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            Dernière connexion : {{ $user->last_login_at->diffForHumans() }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aperçu de l'image avant envoi
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.alt = 'Aperçu de la photo';
                preview.className = 'img-thumbnail mt-2';
                preview.style.maxWidth = '200px';
                
                const container = document.getElementById('imagePreview');
                container.innerHTML = '';
                container.appendChild(preview);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
