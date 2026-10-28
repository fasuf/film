<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Cast, Peran};
use Illuminate\Http\Request;

class CastController extends Controller
{
    /**
     * Display a listing of casts with search & pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Cast::withCount('films')->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('age', 'like', "%{$search}%");
            });
        }

        $casts = $query->paginate(10)->withQueryString();

        return view('admin.casts.index', compact('casts', 'search'));
    }

    /**
     * Show the form for creating a new cast.
     */
    public function create()
    {
        return view('admin.casts.create');
    }

    /**
     * Store a newly created cast in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:45'],
            'age'  => ['required', 'integer', 'min:1', 'max:120'],
            'bio'  => ['required', 'string'],
        ], [
            'name.required' => 'Nama aktor/cast wajib diisi.',
            'name.max'      => 'Nama aktor maksimal 45 karakter.',
            'age.required'  => 'Umur wajib diisi.',
            'bio.required'  => 'Biografi singkat wajib diisi.',
        ]);

        $cast = Cast::create([
            'name' => $request->name,
            'age'  => $request->age,
            'bio'  => $request->bio,
        ]);

        return redirect()->route('admin.casts.index')
            ->with('success', 'Cast/Aktor "' . $cast->name . '" berhasil ditambahkan!');
    }

    /**
     * Display the specified cast.
     */
    public function show(Cast $cast)
    {
        $cast->load(['films.genre', 'perans.film']);

        return view('admin.casts.show', compact('cast'));
    }

    /**
     * Show the form for editing the specified cast.
     */
    public function edit(Cast $cast)
    {
        return view('admin.casts.edit', compact('cast'));
    }

    /**
     * Update the specified cast in storage.
     */
    public function update(Request $request, Cast $cast)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:45'],
            'age'  => ['required', 'integer', 'min:1', 'max:120'],
            'bio'  => ['required', 'string'],
        ], [
            'name.required' => 'Nama aktor/cast wajib diisi.',
            'name.max'      => 'Nama aktor maksimal 45 karakter.',
            'age.required'  => 'Umur wajib diisi.',
            'bio.required'  => 'Biografi singkat wajib diisi.',
        ]);

        $cast->update([
            'name' => $request->name,
            'age'  => $request->age,
            'bio'  => $request->bio,
        ]);

        return redirect()->route('admin.casts.index')
            ->with('success', 'Data Cast "' . $cast->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified cast from storage.
     */
    public function destroy(Cast $cast)
    {
        $name = $cast->name;

        // Delete pivot records
        $cast->perans()->delete();
        $cast->delete();

        return redirect()->route('admin.casts.index')
            ->with('success', 'Cast "' . $name . '" berhasil dihapus!');
    }
}
