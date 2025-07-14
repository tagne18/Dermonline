<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Crée un utilisateur patient
        $this->patient = User::factory()->create([
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Teste l'accès à toutes les pages principales du patient
     */
    public function test_patient_pages_are_accessible()
    {
        $this->actingAs($this->patient);

        $routes = [
            route('patient.dashboard'),
            route('patient.appointments.index'),
            route('patient.consultations.index'),
            route('patient.documents.index'),
            route('abonnements.index'),
            route('annonces.index'),
            route('temoignages.index'),
            route('messages.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
} 