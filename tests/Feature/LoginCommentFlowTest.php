<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Film;
use App\Models\Genre;

class LoginCommentFlowTest extends TestCase
{
    /**
     * Test 1: Button on movie show page has correct href and no modal attributes.
     */
    public function test_guest_sees_proper_login_button_without_modal_attributes(): void
    {
        $film = Film::first();
        if (!$film) {
            $this->markTestSkipped('No films in database.');
        }

        $response = $this->get(route('movies.show', $film->id));

        $response->assertStatus(200);
        $expectedUrl = route('login', ['redirect' => route('movies.show', $film->id) . '#komentar']);
        $response->assertSee($expectedUrl, false);
        $response->assertDontSee('data-toggle="modal" data-target="#myModal"' . "\n" . '                   class="btn btn-warning"', false);
        $response->assertDontSee('data-toggle="modal" data-target="#myModal" class="btn btn-warning"', false);
    }

    /**
     * Test 2: Full login page displays notice when redirect parameter is present.
     */
    public function test_login_page_displays_notice_when_redirected(): void
    {
        $response = $this->get(route('login', ['redirect' => 'http://localhost/movies/1#komentar']));

        $response->assertStatus(200);
        $response->assertSee('Silakan login terlebih dahulu untuk melanjutkan.');
        $response->assertSee('name="redirect_to"', false);
    }

    /**
     * Test 3: Normal direct login page does not display the redirect notice.
     */
    public function test_normal_login_page_does_not_display_notice(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertDontSee('Silakan login terlebih dahulu untuk melanjutkan.');
    }

    /**
     * Test 4: Logging in redirects back to intended film URL with #komentar.
     */
    public function test_login_redirects_back_to_intended_film_with_komentar_anchor(): void
    {
        $film = Film::first();
        if (!$film) {
            $this->markTestSkipped('No films in database.');
        }

        $user = User::where('email', 'admin@example.com')->first();
        if (!$user) {
            $this->markTestSkipped('No admin user in database.');
        }

        $targetUrl = route('movies.show', $film->id) . '#komentar';

        $response = $this->post(route('login.post'), [
            'email'       => 'admin@example.com',
            'password'    => '12345678',
            'redirect_to' => $targetUrl,
        ]);

        $response->assertRedirect($targetUrl);
    }

    /**
     * Test 5: Authenticated user sees comment form instead of login box.
     */
    public function test_authenticated_user_sees_comment_form(): void
    {
        $film = Film::first();
        if (!$film) {
            $this->markTestSkipped('No films in database.');
        }

        $user = User::where('email', 'admin@example.com')->first();
        if (!$user) {
            $this->markTestSkipped('No admin user in database.');
        }

        $response = $this->actingAs($user)->get(route('movies.show', $film->id));

        $response->assertStatus(200);
        $response->assertSee('Berkomentar sebagai:');
        $response->assertSee('KIRIM KOMENTAR');
        $response->assertDontSee('Login untuk berkomentar');
    }

    /**
     * Test 6: Backend comment route strictly guards against guests.
     */
    public function test_comment_route_blocks_guests(): void
    {
        $film = Film::first();
        if (!$film) {
            $this->markTestSkipped('No films in database.');
        }

        $response = $this->post(route('comments.store', $film->id), [
            'comment' => 'Test comment without login',
            'rating'  => 8,
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }
}
