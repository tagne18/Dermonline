@extends('layouts.medecin')

@section('title', 'Nouvelle annonce')

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
                            <i class="fas fa-plus-circle me-2"></i>Nouvelle annonce
                        </h5>
                        <a href="{{ route('medecin.new-annonces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('medecin.new-annonces.store') }}" method="POST" enctype="multipart/form-data" id="annonceForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Titre -->
                                <div class="mb-4">
                                    <label for="titre" class="form-label fw-bold">Titre de l'annonce <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('titre') is-invalid @enderror" 
                                           id="titre" name="titre" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contenu -->
                                <div class="mb-4">
                                    <label for="contenu" class="form-label fw-bold">Contenu <span class="text-danger">*</span></label>
                                    <textarea class="form-control ckeditor @error('contenu') is-invalid @enderror" 
                                              id="contenu" name="contenu" rows="15">{{ old('contenu') }}</textarea>
                                    @error('contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                @push('scripts')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        ClassicEditor
                                            .create(document.querySelector('#contenu'), {
                                                // Configuration de la barre d'outils
                                                toolbar: [
                                                    'heading', '|',
                                                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                                                    'blockQuote', 'insertTable', 'mediaEmbed', '|',
                                                    'undo', 'redo'
                                                ],
                                                language: 'fr',
                                                placeholder: 'Saisissez votre contenu ici...',
                                            })
                                            .catch(error => {
                                                console.error('Erreur lors de l\'initialisation de CKEditor:', error);
                                            });
                                    });
                                </script>
                                @endpush

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
                                                    <img id="imagePreview" src="{{ asset('assets/img/no-image-available.jpg') }}" 
                                                         class="img-thumbnail w-100 h-100" style="object-fit: cover;" alt="Aperçu de l'image">
                                                    <button type="button" id="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 d-none">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                       id="image" name="image" accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Format recommandé : 1200x630px. Taille max : 2MB</div>
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
                                                   id="publier" name="publier" value="1" {{ old('publier') ? 'checked' : '' }}>
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
                                            <h6 id="titrePreview" class="mb-2">Titre de l'annonce</h6>
                                            <p id="contenuPreview" class="small text-muted mb-2">
                                                Aperçu du contenu de l'annonce...
                                            </p>
                                            <div class="text-end">
                                                <small class="text-muted">Publié le {{ now()->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('medecin.new-annonces.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="brouillon" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-1"></i> Enregistrer le brouillon
                                </button>
                                <button type="submit" name="action" value="publier" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Publier l'annonce
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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

    // Aperçu de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').src = event.target.result;
                document.getElementById('removeImage').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Suppression de l'image
    document.getElementById('removeImage').addEventListener('click', function() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').src = '{{ asset('assets/img/no-image-available.jpg') }}';
        this.classList.add('d-none');
    });

    // Mise à jour de l'aperçu
    function updatePreview() {
        const titre = document.getElementById('titre').value || 'Titre de l\'annonce';
        const contenu = document.getElementById('contenu').value 
            ? document.getElementById('contenu').value.replace(/<[^>]*>?/gm, '').substring(0, 150) + '...' 
            : 'Aperçu du contenu de l\'annonce...';
        
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
        
        // Sauvegarder le contenu de TinyMCE
        tinymce.triggerSave();
    });

    // Initialisation de l'aperçu
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });
</script>
@endpush
