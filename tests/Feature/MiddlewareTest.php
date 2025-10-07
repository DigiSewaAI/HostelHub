<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Hostel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions for testing
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'student']);
        Permission::create(['name' => 'booking.create']);
        Permission::create(['name' => 'payment.view']);
    }

    /**
     * Ensure owner cannot create more hostels once plan limit reached
     */
    public function test_subscription_limit_middleware_blocks_owner_when_limit_reached(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        // Assume owner's plan limit = 1 hostel
        // Create a hostel for this owner (already reached limit)
        Hostel::factory()->create(['user_id' => $owner->id]);

        // Simulate hitting the route to create another hostel
        $response = $this->actingAs($owner)
            ->get('/owner/hostels/create');

        // Expect redirect with error message from middleware
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Ensure user with correct permission can access route
     */
    public function test_permission_middleware_allows_access_with_correct_permission(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('booking.create');

        // Create a fake route (simulated existing route)
        $response = $this->actingAs($user)
            ->get('/student/bookings/create');

        // Assuming route exists and permission is valid
        $response->assertStatus(200);
    }

    /**
     * Ensure role or permission middleware allows either one
     */
    public function test_role_or_permission_middleware_works_with_either(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');
        $user->givePermissionTo('payment.view');

        $response = $this->actingAs($user)
            ->get('/payments');

        $response->assertStatus(200);
    }
}
