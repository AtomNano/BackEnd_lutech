<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Ticket;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_create_ticket()
    {
        $response = $this->postJson('/api/v1/tickets', ['subject' => 'Test']);
        $response->assertStatus(401); // Harus 401
    }

    public function test_validation_fails_when_customer_id_is_missing()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/tickets', ['subject' => 'Test']);
        $response->assertStatus(422) // Unprocessable Entity
            ->assertJsonValidationErrors(['customer_id']);
    }
}
