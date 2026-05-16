<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use App\Services\WorkspaceContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WorkspaceLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_creation_provisions_a_business_workspace_and_active_workspace(): void
    {
        $user = User::factory()->create();
        $workspace = $user->workspaces()->first();

        $this->assertNotNull($workspace);
        $this->assertSame('business', $workspace->type);
        $this->assertTrue((bool) $workspace->is_default);
        $this->assertSame($workspace->id, $user->fresh()->active_workspace_id);
    }

    public function test_workspace_context_falls_back_to_default_workspace_without_persisting_user_state(): void
    {
        $user = User::factory()->create();
        $workspace = $user->workspaces()->firstOrFail();

        $user->update(['active_workspace_id' => null]);

        $this->actingAs($user->fresh());

        $resolvedWorkspaceId = app(WorkspaceContext::class)->getWorkspaceId();

        $this->assertSame($workspace->id, $resolvedWorkspaceId);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'active_workspace_id' => null,
        ]);
    }

    public function test_setting_default_workspace_syncs_active_workspace_id(): void
    {
        $user = User::factory()->create();
        $originalWorkspace = $user->workspaces()->firstOrFail();
        $secondWorkspace = Workspace::create([
            'user_id' => $user->id,
            'name' => 'Finance Ops',
            'type' => 'business',
        ]);

        Sanctum::actingAs($user->fresh());

        $response = $this->patchJson("/api/v1/workspaces/{$secondWorkspace->id}/default");

        $response->assertOk();
        $this->assertSame($secondWorkspace->id, $user->fresh()->active_workspace_id);
        $this->assertTrue($secondWorkspace->fresh()->is_default);
        $this->assertFalse($originalWorkspace->fresh()->is_default);
    }

    public function test_ticket_creation_uses_default_workspace_when_active_workspace_is_null(): void
    {
        $user = User::factory()->create();
        $workspace = $user->workspaces()->firstOrFail();

        $user->update(['active_workspace_id' => null]);
        Sanctum::actingAs($user->fresh());

        $response = $this->postJson('/api/v1/tickets', [
            'nama' => 'Budi',
            'whatsapp' => '08123456789',
            'jenis_device' => 'LAPTOP',
            'merk_device' => 'Lenovo',
            'subject' => 'Laptop tidak menyala',
            'description' => 'Unit mati total saat tombol power ditekan.',
            'estimasi' => 150000,
            'priority' => 'high',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.subject', 'Laptop tidak menyala')
            ->assertJsonPath('data.customer.whatsapp', '08123456789');

        $this->assertDatabaseHas('customers', [
            'nama' => 'Budi',
            'whatsapp' => '08123456789',
            'workspace_id' => $workspace->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Laptop tidak menyala',
            'workspace_id' => $workspace->id,
        ]);
    }
}
