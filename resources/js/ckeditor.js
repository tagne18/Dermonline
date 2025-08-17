import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// Initialisation de CKEditor sur tous les textarea avec la classe 'ckeditor'
document.addEventListener('DOMContentLoaded', function() {
    const editorElements = document.querySelectorAll('.ckeditor');
    
    editorElements.forEach(element => {
        ClassicEditor
            .create(element, {
                // Configuration de la barre d'outils
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo'
                ],
                // Configuration de la langue
                language: 'fr',
                // Configuration du placeholder
                placeholder: 'Saisissez votre contenu ici...',
            })
            .then(editor => {
                console.log('CKEditor a été initialisé avec succès');
            })
            .catch(error => {
                console.error('Erreur lors de l\'initialisation de CKEditor:', error);
            });
    });
});
