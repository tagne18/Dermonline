@extends('layouts.medecin')

@section('title', 'Modifier l\'annonce : ' . $newAnnonce->titre)

@push('styles')
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-edit me-2"></i>Modifier l'annonce
                        </h5>
                        <a href="{{ route('medecin.new-annonces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('medecin.new-annonces.update', $newAnnonce) }}" method="POST" enctype="multipart/form-data" id="annonceForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Titre -->
                                <div class="mb-4">
                                    <label for="titre" class="form-label fw-bold">Titre de l'annonce <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('titre') is-invalid @enderror" 
                                           id="titre" name="titre" value="{{ old('titre', $newAnnonce->titre) }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contenu -->
                                <div class="mb-4">
                                    <label for="contenu" class="form-label fw-bold">Contenu <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                              id="contenu" name="contenu" rows="15">{{ old('contenu', $newAnnonce->contenu) }}</textarea>
                                    @error('contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image de couverture -->
                                <div class="card mb-4 border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Image de couverture</h6>
                                        <small class="text-muted">L'image sera affichée en haut de votre annonce (optionnel)</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-4">
                                                <div class="position-relative" style="width: 150px; height: 100px;">
                                                    @if($newAnnonce->image_path)
                                                        <img id="imagePreview" src="{{ $newAnnonce->image_url }}" 
                                                             class="img-thumbnail w-100 h-100" style="object-fit: cover;" alt="Aperçu de l'image">
                                                        <button type="button" id="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                                                    @else
                                                        <img id="imagePreview" src="{{ asset('assets/img/no-image-available.jpg') }}" 
                                                             class="img-thumbnail w-100 h-100" style="object-fit: cover;" alt="Aperçu de l'image">
                                                        <button type="button" id="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 d-none">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                       id="image" name="image" accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Format recommandé : 1200x630px. Taille max : 2MB</div>
                                                @if($newAnnonce->image_path)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" id="keepImage" name="keep_image" value="1" checked>
                                                        <label class="form-check-label" for="keepImage">
                                                            Conserver l'image actuelle
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Options de publication -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Options de publication</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                   id="publier" name="publier" value="1" 
                                                   {{ old('publier', $newAnnonce->estPubliee()) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="publier">Publier maintenant</label>
                                        </div>
                                        <div class="alert alert-info mb-0">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                Si désactivé, l'annonce sera enregistrée comme brouillon.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Informations</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled small mb-0">
                                            <li class="mb-2">
                                                <i class="far fa-calendar-plus me-2 text-muted"></i>
                                                <span class="text-muted">Créée le :</span> 
                                                <span class="fw-medium">{{ $newAnnonce->created_at->format('d/m/Y H:i') }}</span>
                                            </li>
                                            @if($newAnnonce->date_publication)
                                                <li class="mb-2">
                                                    <i class="far fa-calendar-check me-2 text-muted"></i>
                                                    <span class="text-muted">Publiée le :</span> 
                                                    <span class="fw-medium">{{ $newAnnonce->date_publication->format('d/m/Y H:i') }}</span>
                                                </li>
                                            @endif
                                            <li>
                                                <i class="far fa-edit me-2 text-muted"></i>
                                                <span class="text-muted">Dernière modification :</span> 
                                                <span class="fw-medium">{{ $newAnnonce->updated_at->format('d/m/Y H:i') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Aperçu -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Aperçu</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small text-muted">
                                            Votre annonce sera visible par les patients sur la page d'accueil du site.
                                        </p>
                                        <div class="border rounded p-3 bg-light">
                                            <h6 id="titrePreview" class="mb-2">{{ $newAnnonce->titre }}</h6>
                                            <p id="contenuPreview" class="small text-muted mb-2">
                                                {{ Str::limit(strip_tags($newAnnonce->contenu), 150) }}
                                            </p>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    Modifiée le {{ now()->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="far fa-trash-alt me-1"></i> Supprimer
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('medecin.new-annonces.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <button type="submit" name="action" value="brouillon" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-1"></i> Enregistrer le brouillon
                                </button>
                                <button type="submit" name="action" value="publier" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer définitivement cette annonce ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('medecin.new-annonces.destroy', $newAnnonce) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="far fa-trash-alt me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialisation de TinyMCE
    tinymce.init({
        selector: '#contenu',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        menubar: false,
        height: 400,
        images_upload_url: '{{ route("medecin.new-annonces.upload.image") }}',
        automatic_uploads: true,
        images_upload_credentials: true,
        images_reuse_filename: true,
        file_picker_types: 'image',
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            
            input.onchange = function() {
                var file = this.files[0];
                
                var reader = new FileReader();
                reader.onload = function() {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            
            input.click();
        },
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
                updatePreview();
            });
        }
    });

    // Gestion de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').src = event.target.result;
                document.getElementById('removeImage').classList.remove('d-none');
                if (document.getElementById('keepImage')) {
                    document.getElementById('keepImage').checked = false;
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Suppression de l'image
    document.getElementById('removeImage').addEventListener('click', function() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').src = '{{ asset('assets/img/no-image-available.jpg') }}';
        this.classList.add('d-none');
        if (document.getElementById('keepImage')) {
            document.getElementById('keepImage').checked = false;
        }
        document.getElementById('removeImageInput').value = '1';
    });

    // Mise à jour de l'aperçu
    function updatePreview() {
        const titre = document.getElementById('titre').value || '{{ $newAnnonce->titre }}';
        const contenu = document.getElementById('contenu').value 
            ? document.getElementById('contenu').value.replace(/<[^>]*>?/gm, '').substring(0, 150) + '...' 
            : '{{ Str::limit(strip_tags($newAnnonce->contenu), 150) }}';
        
        document.getElementById('titrePreview').textContent = titre;
        document.getElementById('contenuPreview').textContent = contenu;
    }

    // Écouteurs d'événements pour la mise à jour en temps réel
    document.getElementById('titre').addEventListener('input', updatePreview);
    
    // Gestion du formulaire
    document.getElementById('annonceForm').addEventListener('submit', function(e) {
        const action = e.submitter ? e.submitter.value : '';
        
        // Mettre à jour le champ caché de publication si nécessaire
        if (action === 'publier') {
            document.getElementById('publier').checked = true;
        } else if (action === 'brouillon') {
            document.getElementById('publier').checked = false;
        }
        
        // Désactiver le champ keep_image si une nouvelle image est téléchargée
        if (document.getElementById('image').files.length > 0 && document.getElementById('keepImage')) {
            document.getElementById('keepImage').checked = false;
        }
        
        // Sauvegarder le contenu de TinyMCE
        tinymce.triggerSave();
    });

    // Initialisation de l'aperçu
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
        
        // Si l'image est déjà définie, on affiche le bouton de suppression
        @if($newAnnonce->image_path)
            document.getElementById('removeImage').classList.remove('d-none');
        @endif
    });
</script>
@endpush
