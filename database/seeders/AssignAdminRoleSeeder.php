<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'tagnefranckloic@gmail.com')->first();

        if (!$user) {
            $this->command->error("❌ Utilisateur non trouvé !");
            return;
        }

        if ($user->role !== 'admin') {
            $user->role = 'admin';
            $user->save();
            $this->command->info("✅ Rôle 'admin' attribué à {$user->email}");
        } else {
            $this->command->warn("ℹ️ L'utilisateur a déjà le rôle 'admin'");
        }
    }
}
