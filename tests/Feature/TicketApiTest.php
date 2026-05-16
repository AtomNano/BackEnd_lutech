<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_create_ticket()
    {
        $response = $this->postJson('/api/v1/tickets', ['subject' => 'Test']);
        $response->assertStatus(401); // Harus 401
    }

    public function test_validation_fails_when_required_ticket_fields_are_missing()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/tickets', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nama',
                'whatsapp',
                'jenis_device',
                'merk_device',
                'subject',
                'description',
            ]);
    }
}
