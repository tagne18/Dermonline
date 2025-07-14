<?php

use Illuminate\Support\Facades\Route;
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
    NewsletterController
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
// ROUTES PUBLIQUES
// ==========================

// Page d'accueil
Route::get('/', function () {
    $testimonials = \App\Models\Testimonial::where('approved', true)->latest()->take(6)->get();
    $medecins = \App\Models\User::where('role', 'medecin')
        ->where('is_blocked', false)
        ->latest()
        ->take(4)
        ->get();
    return view('welcome', compact('testimonials', 'medecins'));
})->name('home');

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
// ROUTES ADMIN
// ==========================

// Routes d'authentification admin (sans middleware check.blocked)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// Routes admin protégées (avec middleware check.blocked)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', \App\Http\Middleware\CheckBlockedUser::class])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::delete('/medecins/{id}', [DoctorApplicationController::class, 'destroy'])->name('medecins.destroy');
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
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');

    // Gestion des témoignages
    Route::get('/testimonials', [TemoignageController::class, 'index'])->name('testimonials');
    Route::post('/testimonials/{id}/approve', [TemoignageController::class, 'approve'])->name('testimonials.approve');
    Route::delete('/testimonials/{id}', [TemoignageController::class, 'destroy'])->name('testimonials.destroy');

    // Gestion des abonnements
    Route::get('/abonnements', [AbonnementController::class, 'index'])->name('abonnements.index');
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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Abonnements
    Route::get('/abonnements', [MedecinAbonnementController::class, 'index'])->name('abonnements.index');
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

    // Annonces
    Route::resource('annonces', PatientAnnonceController::class)->names('annonces');

    // Témoignages
    Route::resource('temoignages', PatientTemoignageController::class)->names('temoignages');

    // Documents
    Route::resource('documents', DocumentController::class)->names('documents');

    // Paiement
    Route::get('/paiement/form', [PaymentController::class, 'form'])->name('paiement.form');
    Route::post('/paiement/verify', [PaymentController::class, 'verify'])->name('paiement.verify');

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

