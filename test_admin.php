<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Test de l'utilisateur admin
$admin = User::where('email', 'tagnefranckloic@gmail.com')->first();

if ($admin) {
    echo "✅ Admin trouvé:\n";
    echo "Nom: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Rôle: " . $admin->role . "\n";
    echo "Vérifié: " . ($admin->email_verified_at ? 'Oui' : 'Non') . "\n";
    
    // Test du mot de passe
    $testPassword = 'Cameroun@237';
    if (Hash::check($testPassword, $admin->password)) {
        echo "✅ Mot de passe correct\n";
    } else {
        echo "❌ Mot de passe incorrect\n";
    }
} else {
    echo "❌ Admin non trouvé\n";
}

// Test des routes
echo "\n📋 Routes admin disponibles:\n";
$routes = [
    'admin.login' => '/admin/login',
    'admin.dashboard' => '/admin/dashboard',
    'admin.users.patients' => '/admin/users/patients',
    'admin.doctor_applications.index' => '/admin/doctor_applications',
    'admin.announcements' => '/admin/announcements',
    'admin.testimonials' => '/admin/testimonials',
    'admin.abonnements.index' => '/admin/abonnements',
    'admin.blocked' => '/admin/blocked'
];

foreach ($routes as $name => $path) {
    echo "- $name: $path\n";
}

echo "\n🎯 Pour tester l'application:\n";
echo "1. Va sur: http://127.0.0.1:8001/admin/login\n";
echo "2. Connecte-toi avec:\n";
echo "   Email: tagnefranckloic@gmail.com\n";
echo "   Mot de passe: Cameroun@237\n";
echo "3. Tu seras redirigé vers le dashboard admin\n"; 