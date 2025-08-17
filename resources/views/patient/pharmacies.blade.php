@extends('layouts.app')

@section('title', 'Pharmacies à proximité')

@section('content')
<div class="w-full bg-green-50 py-10 px-2 mb-6" style="min-height:170px;">
    <div class="flex flex-col items-center">
        <div class="rounded-full bg-green-100 p-2 shadow-md mb-2">
            <svg class="w-7 h-7 text-green-500" fill="none" stroke="#24b47e" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="#24b47e" stroke-width="2" fill="#e5faf3"/>
                <path d="M12 8v8M8 12h8" stroke="#24b47e" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-800 mb-1">Pharmacies à proximité</h2>
        <p class="text-gray-500 text-md">Trouvez rapidement une pharmacie proche de votre position.<br><span class="text-green-600 font-medium">Service confidentiel : votre position n’est jamais stockée.</span></p>
    </div>
    <div class="flex justify-center">
    <div id="pharmacy-map" class="mb-6 shadow-md rounded-xl border border-green-100"
        style="width:320px; height:200px; max-width:100%; border-radius:18px; overflow:hidden; box-shadow:0 6px 32px rgba(36,180,126,0.14);">
    </div>
</div>
    <div id="pharmacy-loader" class="flex justify-center items-center py-6">
        <svg class="animate-spin h-8 w-8 text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <span class="text-green-600 font-semibold">Recherche des pharmacies proches…</span>
    </div>
    <form id="search-form" class="mb-4 d-flex justify-content-center">
    <input type="text" id="search-input" class="form-control w-50" placeholder="Rechercher une pharmacie ou une adresse...">
    <button type="submit" class="btn btn-outline-success ms-2">Rechercher</button>
</form>
<div id="pharmacy-list" class="mt-2"></div>
    <div id="pharmacy-error" class="mt-4 text-danger font-semibold text-center"></div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    .pharmacy-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(36,180,126,0.09);
        padding: 18px 20px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .pharmacy-card .icon {
        width: 38px;
        height: 38px;
        background: #e5faf3;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pharmacy-card .itineraire {
        margin-left: auto;
        background: #24b47e;
        color: #fff;
        border-radius: 8px;
        padding: 7px 16px;
        font-weight: 600;
        font-size: 0.98rem;
        transition: background 0.2s;
    }
    .pharmacy-card .itineraire:hover {
        background: #1a8c63;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
let map = L.map('pharmacy-map').setView([0,0], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap',
    maxZoom: 19,
}).addTo(map);

function showError(msg) {
    document.getElementById('pharmacy-error').textContent = msg;
    document.getElementById('pharmacy-loader').style.display = 'none';
}

function showLoader(show) {
    document.getElementById('pharmacy-loader').style.display = show ? 'flex' : 'none';
}

// Géolocalisation du patient
if(navigator.geolocation) {
    showLoader(true);
    navigator.geolocation.getCurrentPosition(function(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        map.setView([lat, lon], 16);
        L.marker([lat, lon], {icon: L.icon({iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', iconSize:[36,36], iconAnchor:[18,36]})}).addTo(map).bindPopup('Vous êtes ici');
        fetchPharmacies(lat, lon);
    }, function() {
        showError('Impossible de récupérer votre position. Veuillez autoriser la géolocalisation ou réessayer.');
    });
} else {
    showError('La géolocalisation n’est pas supportée par votre navigateur.');
}

// Requête aux pharmacies via Nominatim (OpenStreetMap)
let pharmaciesData = [];

function renderPharmaciesList(pharmacies) {
    let list = '';
    pharmacies.forEach(ph => {
        const nom = ph.display_name.split(',')[0];
        const adresse = ph.display_name.split(',').slice(1).join(',');
        const itineraire = `https://www.google.com/maps/dir/?api=1&destination=${ph.lat},${ph.lon}`;
        list += `<x-pharmacy-card 
    :name="'${nom}'"
    :address="'${adresse}'"
    :itineraire="'${itineraire}'"
/>
`;
    });
    document.getElementById('pharmacy-list').innerHTML = list;
}

function fetchPharmacies(lat, lon) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&extratags=1&limit=15&amenity=pharmacy&bounded=1&viewbox=${lon-0.02},${lat+0.02},${lon+0.02},${lat-0.02}`;
    fetch(url, {headers: { 'Accept-Language': 'fr' }})
        .then(res => res.json())
        .then(data => {
            showLoader(false);
            pharmaciesData = data;
            if(!data.length) {
                showError('Aucune pharmacie trouvée à proximité.');
                return;
            }
            renderPharmaciesList(data);
        })
        .catch(() => showError('Erreur lors de la récupération des pharmacies.'));
}

// Recherche dynamique et géographique
const searchInput = document.getElementById('search-input');
const searchForm = document.getElementById('search-form');

if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (!query) {
            // Si champ vide, revenir à la position actuelle (géolocalisation)
            if(navigator.geolocation) {
                showLoader(true);
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    map.setView([lat, lon], 16);
                    fetchPharmacies(lat, lon);
                }, function() {
                    showError('Impossible de récupérer votre position. Veuillez autoriser la géolocalisation ou réessayer.');
                });
            }
            return;
        }
        // Géocodage Nominatim de l'adresse/ville saisie
        showLoader(true);
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&accept-language=fr`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    showLoader(false);
                    showError('Adresse ou ville introuvable.');
                    return;
                }
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                map.setView([lat, lon], 15);
                fetchPharmacies(lat, lon);
            })
            .catch(() => {
                showLoader(false);
                showError('Erreur lors de la recherche d\'adresse.');
            });
    });
}

// Filtrage dynamique sur la liste déjà chargée (utile si on veut filtrer par nom après avoir cherché une localité)
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        if (!query) {
            renderPharmaciesList(pharmaciesData);
            return;
        }
        const filtered = pharmaciesData.filter(ph => {
            const nom = ph.display_name.split(',')[0].toLowerCase();
            const adresse = ph.display_name.split(',').slice(1).join(',').toLowerCase();
            return nom.includes(query) || adresse.includes(query);
        });
        renderPharmaciesList(filtered);
    });
}

</script>
@endpush
