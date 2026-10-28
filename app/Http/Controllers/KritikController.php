<?php

namespace App\Http\Controllers;

use App\Models\{Kritik, Film};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KritikController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Film $film)
    {
        // 1. Strict backend authentication guard
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu untuk berkomentar.'
                ], 401);
            }

            return redirect()->guest(route('login', ['redirect' => route('movies.show', $film->id) . '#komentar']))
                ->with('notice', 'Silakan login terlebih dahulu untuk melanjutkan.');
        }

        // 2. Validation
        $request->validate([
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
            'rating'  => ['nullable', 'integer', 'min:1', 'max:10'],
        ], [
            'comment.required' => 'Komentar tidak boleh kosong.',
            'comment.min'      => 'Komentar minimal 3 karakter.',
            'comment.max'      => 'Komentar maksimal 1000 karakter.',
            'rating.integer'   => 'Format rating tidak valid.',
            'rating.min'       => 'Rating minimal adalah 1.',
            'rating.max'       => 'Rating maksimal adalah 10.',
        ]);

        // 3. XSS Sanitization: strip dangerous HTML tags and convert special characters
        $sanitizedComment = htmlspecialchars(strip_tags(trim($request->comment)), ENT_QUOTES, 'UTF-8');

        // 4. user_id strictly retrieved from authenticated session, NEVER from form input
        Kritik::create([
            'comment' => $sanitizedComment,
            'rating'  => (int) ($request->rating ?? 10),
            'film_id' => $film->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->to(route('movies.show', $film->id) . '#comments')
            ->with('success', 'Ulasan dan komentar Anda berhasil dipublikasikan!');
    }
}
