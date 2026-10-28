<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AdminUserTest extends TestCase
{
    public function test_admin_can_access_user_management_page(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (!$admin) {
            $this->markTestSkipped('No admin user found.');
        }

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Data Pengguna');
        $displayedUser = collect($response->viewData('users')->items())->first(fn($u) => $u->id !== $admin->id);
        if ($displayedUser) {
            $response->assertSee(route('admin.users.updateRole', $displayedUser->id));
        }
    }

    public function test_admin_can_delete_user_with_comments(): void
    {
        $admin = User::where('role_id', 2)->first();
        if (!$admin) {
            $this->markTestSkipped('No admin user found.');
        }

        // Create a temporary user with a profile and a comment
        $profile = \App\Models\Profile::create([
            'alamat' => 'Test Street',
            'umur'   => 25,
            'bio'    => 'Test Bio',
        ]);

        $user = User::create([
            'name'       => 'Temporary User to Delete',
            'email'      => 'temp_delete_' . time() . '@example.com',
            'password'   => 'password123',
            'role_id'    => 1,
            'profile_id' => $profile->id,
        ]);

        $film = \App\Models\Film::first();
        if ($film) {
            \App\Models\Kritik::create([
                'comment' => 'Test comment that previously caused foreign key violation',
                'rating'  => 9,
                'film_id' => $film->id,
                'user_id' => $user->id,
            ]);
        }

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $user->id));

        $deleteResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('kritiks', ['user_id' => $user->id]);
    }
}
