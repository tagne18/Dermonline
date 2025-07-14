<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Supprimer les anciens admins s'ils existent
        User::where('email', 'loictagne07@gmail.com')->delete();
        User::where('email', 'tagnefranckloic@gmail.com')->delete();
        
        // Créer le compte administrateur principal
        User::create([
            'name' => 'tagne loic franck',
            'email' => 'tagnefranckloic@gmail.com',
            'password' => Hash::make('Cameroun@237'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
