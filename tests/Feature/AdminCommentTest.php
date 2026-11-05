<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Kritik;
use App\Models\User;
use Tests\TestCase;

class AdminCommentTest extends TestCase
{
    public function test_admin_can_delete_a_comment(): void
    {
        $admin = User::where('role_id', 2)->first();
        $film = Film::first();

        if (! $admin || ! $film) {
            $this->markTestSkipped('An admin user and a film are required.');
        }

        $comment = Kritik::create([
            'comment' => 'Temporary comment for delete test.',
            'rating' => 8,
            'film_id' => $film->id,
            'user_id' => $admin->id,
        ]);

        try {
            $this->actingAs($admin)
                ->get(route('admin.comments.index'))
                ->assertOk()
                ->assertSee(route('admin.comments.destroy', $comment));

            $response = $this->delete(route('admin.comments.destroy', $comment));

            $response->assertRedirect(route('admin.comments.index'));
            $response->assertSessionHas('success', 'Komentar berhasil dihapus.');
            $this->assertDatabaseMissing('kritiks', ['id' => $comment->id]);
        } finally {
            Kritik::whereKey($comment->id)->delete();
        }
    }
}
