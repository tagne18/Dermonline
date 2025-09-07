<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleRedirectController;
use App\Http\Controllers\Admin\{
    AdminLoginController,
    DashboardController,
    AnnonceController,
    ConsultationController,
    TemoignageController,
    UtilisateurController,
    AbonnementController,
    DoctorApplicationController as AdminDoctorApplicationController,
    ContactController,
    NewsletterController,
    ProfileController as AdminProfileController
};
use App\Http\Controllers\Patient\{
   AnnonceController as PatientAnnonceController,
    PatientDashboardController,
    AppointmentController,
    ConsultationController as PatientConsultationController,
    DocumentController,
    MessageController,
    TemoignageController as PatientTemoignageController,
    AbonnementController as PatientAbonnementController,
    PaymentController
};
use App\Http\Controllers\Medecin\{
    DashboardController as MedecinDashboardController,
    AbonnementController as MedecinAbonnementController,
    ConsultationController as MedecinConsultationController,
    AnnonceController as MedecinAnnonceController,
    ProfileController,
    MessageController as MedecinMessageController,
    DossierController
};
use App\Http\Controllers\DoctorApplicationController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\Community\MessageController as CommunityMessageController;
use App\Exports\DocteursExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\DoctorAccountCreated;
use App\Models\User;
use App\Http\Middleware\CheckBlockedUser;
use App\Http\Middleware\RoleMiddleware;

// ==========================
// Analyse IA image (upload)
Route::post('/ia/gemini-image', [App\Http\Controllers\GeminiController::class, 'analyzeImage'])->middleware('auth');

// Historique des analyses d'images (patient)
Route::get('/patient/analyses', [App\Http\Controllers\Patient\ImageAnalysisController::class, 'index'])->middleware(['auth']);

// ROUTES PUBLIQUES
// ==========================

// Page d'accueil
Route::get('/', function () {
    $medecins = \App\Models\User::where('role', 'medecin')
        ->whereNull('blocked_at')
        ->get();
    
    $testimonials = \App\Models\Testimonial::with('user')
        ->where('approved', true)
        ->latest()
        ->take(5)
        ->get();
    
    return view('welcome', compact('medecins', 'testimonials'));
})->name('welcome');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Authentification
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Candidature médecin
Route::get('/doctor-application', [DoctorApplicationController::class, 'create'])->name('doctor_application.create');
Route::get('/devenir-medecin', [DoctorApplicationController::class, 'create'])->name('doctor-application.create');
Route::post('/doctor-application', [DoctorApplicationController::class, 'store'])->name('doctor_application.store');

// Export des docteurs
Route::get('/export-docteurs', function () {
    return Excel::download(new DocteursExport, 'docteurs.xlsx');
});

// Test email
Route::get('/test-mail', function () {
    $user = new User([
        'name' => 'Tagne loic franck',
        'email' => 'loictagne07@gmail.com',
    ]);
    $password = 'motDePasseTest123';
    try {
        Mail::to($user->email)->send(new DoctorAccountCreated($user, $password));
        return 'Email envoyé avec succès à ' . $user->email;
    } catch (\Exception $e) {
        return 'Erreur lors de l\'envoi : ' . $e->getMessage();
    }
});

// Formulaire de contact (public)
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// Abonnement à la newsletter (public)
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Route pour l'IA Gemini (public)
Route::post('/ia/gemini', [App\Http\Controllers\GeminiController::class, 'ask'])->name('ia.gemini');

// ==========================
// ROUTES PATIENT : Ordonnances et Examens
// ==========================
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ExamResultController;

Route::middleware(['auth', 'verified', App\Http\Middleware\RoleMiddleware::class . ':patient'])->prefix('patient')->name('patient.')->group(function () {
    // Pharmacies à proximité
    Route::get('/pharmacies', [\App\Http\Controllers\Patient\PharmacyController::class, 'index'])->name('pharmacies.index');
    // Ordonnances
    Route::get('/ordonnances', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'index'])->name('ordonnances.index');
    Route::get('/ordonnances/derniere', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'latest'])->name('ordonnances.latest');
    Route::get('/ordonnances/{id}', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'show'])->name('ordonnances.show');
    Route::get('/ordonnances/{id}/view', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'view'])->name('ordonnances.view');
    Route::get('/ordonnances/{id}/download', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'download'])->name('ordonnances.download');
    
    // Fichiers joints aux ordonnances
    Route::get('/ordonnances/fichiers/{fichierId}/afficher', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'showFichier'])->name('ordonnances.fichiers.afficher');
    Route::get('/ordonnances/fichiers/{fichierId}/telecharger', [\App\Http\Controllers\Patient\OrdonnanceController::class, 'downloadFichier'])->name('ordonnances.fichiers.telecharger');

    // Examens
    Route::get('/examens', [ExamResultController::class, 'index'])->name('examens');
    Route::get('/examens/{id}', [ExamResultController::class, 'show'])->name('examens.show');
    Route::get('/examens/{id}/download', [ExamResultController::class, 'download'])->name('examens.download');
});

// ==========================
// ROUTES ADMIN
// ==========================

// Routes d'authentification admin (sans middleware check.blocked)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// Routes admin protégées (avec middleware check.blocked)
// Routes API pour le chargement asynchrone des données
Route::prefix('api/admin')->name('admin.api.')->middleware(['auth', 'verified', \App\Http\Middleware\CheckBlockedUser::class])->group(function () {
    // Récupération des statistiques du dashboard
    Route::get('/dashboard/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // Récupération des notifications récentes
    Route::get('/dashboard/notifications', [\App\Http\Controllers\Admin\DashboardController::class, 'getNotifications'])->name('dashboard.notifications');
    
    // Récupération de la liste des patients
    Route::get('/dashboard/patients', [\App\Http\Controllers\Admin\DashboardController::class, 'getPatients'])->name('dashboard.patients');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', \App\Http\Middleware\CheckBlockedUser::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des médicaments
    Route::resource('medicaments', \App\Http\Controllers\Admin\MedicamentController::class);
    Route::get('medicaments-search', [\App\Http\Controllers\Admin\MedicamentController::class, 'search'])
        ->name('medicaments.search');
    
    // Gestion des ordonnances
    Route::resource('ordonnances', \App\Http\Controllers\Admin\OrdonnanceController::class);
    Route::get('ordonnances/{ordonnance}/download', [\App\Http\Controllers\Admin\OrdonnanceController::class, 'download'])
        ->name('ordonnances.download');
    
    // Gestion du profil administrateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
    });

    // Gestion des utilisateurs
    Route::get('/users/patients', [UtilisateurController::class, 'index'])->name('users.patients');
    Route::get('/users/patients/{id}', [UtilisateurController::class, 'show'])->name('users.patients.show');
    Route::delete('/users/patients/{id}', [UtilisateurController::class, 'destroy'])->name('users.patients.destroy');
    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::post('/bloquer/{id}', [UtilisateurController::class, 'bloquer'])->name('utilisateurs.bloquer');
    Route::post('/debloquer/{id}', [UtilisateurController::class, 'debloquer'])->name('utilisateurs.debloquer');
    Route::get('/blocked', [UtilisateurController::class, 'blocked'])->name('blocked');
    
    // Gestion des médecins
    Route::get('/medecins', [AdminDoctorApplicationController::class, 'medecins'])->name('medecins.index');
    Route::get('/medecins/{id}', [AdminDoctorApplicationController::class, 'showMedecin'])->name('medecins.show');
    Route::get('/medecins/export/csv', [AdminDoctorApplicationController::class, 'exportMedecins'])->name('medecins.export');
    Route::get('/medecins/statistiques', [AdminDoctorApplicationController::class, 'statistiques'])->name('medecins.statistiques');
    Route::delete('/medecins/{id}', [AdminDoctorApplicationController::class, 'destroy'])->name('medecins.destroy');
    Route::post('/medecins/{id}/bloquer', [AdminDoctorApplicationController::class, 'bloquerMedecin'])->name('medecins.bloquer');
    Route::post('/medecins/{id}/debloquer', [AdminDoctorApplicationController::class, 'debloquerMedecin'])->name('medecins.debloquer');
    Route::post('/medecins/{id}/alerte', [AdminDoctorApplicationController::class, 'envoyerAlerte'])->name('medecins.alerte');
    
    // Candidatures médecins
    Route::resource('doctor_applications', AdminDoctorApplicationController::class);
    Route::patch('doctor_applications/{id}/approve', [AdminDoctorApplicationController::class, 'approve'])->name('doctor_applications.approve');
    Route::patch('doctor_applications/{id}/reject', [AdminDoctorApplicationController::class, 'reject'])->name('doctor_applications.reject');
    Route::post('doctor_applications/{id}/create-user', [AdminDoctorApplicationController::class, 'createUser'])->name('doctor_applications.create_user');
    Route::delete('doctor_applications/rejected', [AdminDoctorApplicationController::class, 'deleteRejected'])->name('doctor_applications.deleteRejected');
    
    // Gestion des annonces
    Route::resource('annonces', AnnonceController::class);
    Route::get('/announcements', [AnnonceController::class, 'index'])->name('announcements'); // Alias pour compatibilité
    
    // Gestion des consultations
    Route::resource('consultations', ConsultationController::class)->only(['index', 'show', 'update']);
    Route::put('consultations/{consultation}/status', [ConsultationController::class, 'updateStatus'])->name('consultations.status.update');

    // Gestion des témoignages
    Route::get('/testimonials', [TemoignageController::class, 'index'])->name('testimonials');
    Route::post('/testimonials/{id}/approve', [TemoignageController::class, 'approve'])->name('testimonials.approve');
    Route::delete('/testimonials/{id}', [TemoignageController::class, 'destroy'])->name('testimonials.destroy');

    // Gestion des abonnements
    Route::resource('abonnements', AbonnementController::class)->except(['create', 'edit']);
    Route::get('abonnements/export', [AbonnementController::class, 'export'])->name('abonnements.export');
    Route::get('/subscriptions', [AbonnementController::class, 'index'])->name('subscriptions'); // Alias pour compatibilité

    // Gestion des contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

    // Gestion des newsletters
    Route::get('/newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('/newsletters/send', [NewsletterController::class, 'send'])->name('newsletters.send');
});

// ==========================
// ROUTES MÉDECIN
// ==========================
Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckBlockedUser::class])->prefix('medecin')->name('medecin.')->group(function () {
    Route::get('/dashboard', [MedecinDashboardController::class, 'index'])->name('dashboard');

    // Gérer le profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Gestion des nouvelles annonces
    Route::prefix('new-annonces')->name('new-annonces.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'store'])->name('store');
        Route::get('/{newAnnonce}', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'show'])->name('show');
        Route::get('/{newAnnonce}/edit', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'edit'])->name('edit');
        Route::put('/{newAnnonce}', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'update'])->name('update');
        Route::delete('/{newAnnonce}', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'destroy'])->name('destroy');
        
        // Upload d'image pour l'éditeur
        Route::post('/upload-image', [\App\Http\Controllers\Medecin\NewAnnonceController::class, 'uploadImage'])
            ->name('upload.image');
    });
    
    // Gestion du planning
    // Gestion des plannings
    Route::prefix('planning')->name('planning.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Medecin\PlanningController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Medecin\PlanningController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Medecin\PlanningController::class, 'store'])->name('store');
        Route::get('/{planning}', [\App\Http\Controllers\Medecin\PlanningController::class, 'show'])->name('show');
        Route::get('/{planning}/edit', [\App\Http\Controllers\Medecin\PlanningController::class, 'edit'])->name('edit');
        Route::put('/{planning}', [\App\Http\Controllers\Medecin\PlanningController::class, 'update'])->name('update');
        Route::delete('/{planning}', [\App\Http\Controllers\Medecin\PlanningController::class, 'destroy'])->name('destroy');
        Route::get('/historique', [\App\Http\Controllers\Medecin\PlanningController::class, 'getHistorique'])->name('historique');
    });
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Abonnements
    Route::get('/abonnements', [MedecinAbonnementController::class, 'index'])->name('abonnements.index');
    Route::get('/patients', [MedecinAbonnementController::class, 'listPatients'])->name('patients.list');
    Route::get('/abonnements/patients', [MedecinAbonnementController::class, 'patients'])->name('abonnements.patients');
    Route::get('/abonnements/{id}/edit', [MedecinAbonnementController::class, 'edit'])->name('abonnements.edit');
    Route::put('/abonnements/{id}', [MedecinAbonnementController::class, 'update'])->name('abonnements.update');

    // Consultations
    Route::get('/consultations', [MedecinConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/{id}', [MedecinConsultationController::class, 'show'])->name('consultations.show');
    Route::post('/consultations/{id}/traiter', [MedecinConsultationController::class, 'traiter'])->name('consultations.traiter');

    // Annonces
    Route::resource('annonces', MedecinAnnonceController::class)->names('annonces');

    // Messages à la communauté
    Route::get('/messages', [MedecinMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MedecinMessageController::class, 'store'])->name('messages.store');

    // Dossiers médicaux
    Route::resource('dossiers', DossierController::class)->names('dossiers');
    Route::get('/dossiers/{id}/download', [DossierController::class, 'download'])->name('dossiers.download');

    // Validation/refus rendez-vous
    Route::post('/rendez-vous/{id}/valider', [\App\Http\Controllers\Medecin\AnnonceController::class, 'validateRdv'])->name('appointments.validate');

    // Reprogrammation de rendez-vous
    Route::post('/appointments/reschedule', [\App\Http\Controllers\Medecin\AnnonceController::class, 'rescheduleRdv'])->name('appointments.reschedule');

    // Messagerie médecin -> patient
    Route::post('/messages/send', [\App\Http\Controllers\Medecin\MessageController::class, 'sendToPatient'])->name('messages.send');
    // Création d'une ordonnance à partir d'un rendez-vous (pré-remplissage du patient)
Route::get('prescriptions/create', [App\Http\Controllers\Medecin\OrdonnanceController::class, 'create'])->name('prescriptions.create');
Route::resource('ordonnances', App\Http\Controllers\Medecin\OrdonnanceController::class)->names('ordonnances');
    Route::resource('examens', App\Http\Controllers\Medecin\ExamenController::class)->names('examens');
    Route::post('/rendez-vous/{id}/refuser', [\App\Http\Controllers\Medecin\AnnonceController::class, 'refuseRdv'])->name('appointments.refuse');
});

// ==========================
// ROUTES PATIENT
// ==========================
Route::middleware(['auth', 'verified', \App\Http\Middleware\RoleMiddleware::class.':patient', \App\Http\Middleware\CheckBlockedUser::class])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

    // Consultations
    Route::get('/consultations', [PatientConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/{id}', [PatientConsultationController::class, 'show'])->name('consultations.show');
    Route::get('/consultations/enligne', [PatientConsultationController::class, 'enligne'])->name('consultation.enligne');
    Route::post('/consultations/{id}/traiter', [PatientConsultationController::class, 'traiter'])->name('consultations.traiter');

    // Messages
    Route::resource('messages', MessageController::class)->names('messages');

    // Abonnements
    Route::resource('abonnements', PatientAbonnementController::class)->names('abonnements');
    Route::get('abonnements/verify', [PatientAbonnementController::class, 'verify'])->name('abonnements.verify');
    Route::post('abonnements/verify', [PatientAbonnementController::class, 'verifyPayment'])->name('abonnements.verifyPayment');

    // Annonces
    Route::resource('annonces', PatientAnnonceController::class)->names('annonces');

    // Témoignages
    Route::resource('temoignages', PatientTemoignageController::class)->names('temoignages');

    // Documents
    Route::resource('documents', DocumentController::class)->names('documents');

    // Paiement
    Route::get('/paiement', [PaymentController::class, 'showForm'])->name('paiement.form');
    Route::post('/paiement/process', [PaymentController::class, 'process'])->name('paiement.process');
    Route::get('/paiement/verify', [PaymentController::class, 'verify'])->name('paiement.verify');

    // Rendez-vous
    Route::resource('appointments', AppointmentController::class)->names('appointments');
});

// ==========================
// ROUTES COMMUNES
// ==========================

// Chat communautaire (accessible à tous les utilisateurs authentifiés)
Route::middleware(['auth', \App\Http\Middleware\CheckBlockedUser::class])->prefix('community')->name('community.')->group(function () {
    Route::get('/messages', [CommunityMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [CommunityMessageController::class, 'store'])->name('messages.store');
});

// Redirection selon rôle
Route::get('/redirect', [RoleRedirectController::class, 'redirect'])->middleware(['auth', 'verified', \App\Http\Middleware\CheckBlockedUser::class])->name('redirect');

// Dashboard par défaut
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', \App\Http\Middleware\CheckBlockedUser::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/solution', function () {
    return view('solution');
})->name('solution');

// Routes de déconnexion personnalisées
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Permettre la déconnexion via GET pour la rétrocompatibilité
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout.get');

