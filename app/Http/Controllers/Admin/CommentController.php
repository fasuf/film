<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kritik;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of comments with search & pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Kritik::with(['user', 'film'])->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('film', function ($fq) use ($search) {
                      $fq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $comments = $query->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments', 'search'));
    }

    /**
     * Remove an inappropriate comment from storage.
     */
    public function destroy(Kritik $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dihapus.');
    }
}
