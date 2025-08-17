// Script pour rendre chaque carte médecin cliquable et afficher une modal Bootstrap

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.voir-profile-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const medecin = JSON.parse(this.getAttribute('data-medecin'));
            // Remplir la modal
            document.getElementById('doctorModalLabel').innerText = medecin.name;
            document.getElementById('doctorModalSpecialite').innerText = medecin.specialite || 'Dermatologue';
            document.getElementById('doctorModalPhoto').src = medecin.profile_photo_url;
            document.getElementById('doctorModalApropos').innerText = medecin.a_propos || '';
            // Afficher la modal
            const modal = new bootstrap.Modal(document.getElementById('doctorModal'));
            modal.show();
        });
    });
});
