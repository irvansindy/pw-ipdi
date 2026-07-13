<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\UserManagementController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();

        $controller = new UserManagementController();
        $response = $controller->destroy(new Request(['user_id' => $user->id]));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($user->fresh()->trashed());
    }

    public function test_user_roles_can_be_updated(): void
    {
        $user = User::factory()->create();
        $initialRole = Role::create(['name' => 'user']);
        $newRole = Role::create(['name' => 'admin']);
        $user->assignRole($initialRole);

        $controller = new UserManagementController();
        $response = $controller->updateRole(new Request([
            'user_id' => $user->id,
            'roles' => [$newRole->id],
        ]));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($user->fresh()->hasRole($newRole->name));
        $this->assertFalse($user->fresh()->hasRole($initialRole->name));
    }
}
