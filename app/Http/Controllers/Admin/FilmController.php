<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Film, Genre, Cast, Peran};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FilmController extends Controller
{
    /**
     * Display a listing of the films with search & pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $genreId = $request->query('genre_id');

        $query = Film::with(['genre', 'actors'])->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhere('sinopsis', 'like', "%{$search}%");
            });
        }

        if (!empty($genreId)) {
            $query->where('genre_id', $genreId);
        }

        $films = $query->paginate(10)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('admin.films.index', compact('films', 'genres', 'search', 'genreId'));
    }

    /**
     * Show the form for creating a new film.
     */
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        $casts = Cast::orderBy('name')->get();

        return view('admin.films.create', compact('genres', 'casts'));
    }

    /**
     * Store a newly created film in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'sinopsis'    => ['required', 'string'],
            'year'        => ['required', 'integer', 'min:1880', 'max:' . (date('Y') + 5)],
            'genre_id'    => ['required', 'exists:genres,id'],
            'duration'    => ['nullable', 'integer', 'min:1', 'max:1000'],
            'rating'      => ['nullable', 'numeric', 'min:0', 'max:10'],
            'poster_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'poster_url'  => ['nullable', 'string', 'max:500'],
        ], [
            'title.required'    => 'Judul film wajib diisi.',
            'sinopsis.required' => 'Deskripsi/sinopsis film wajib diisi.',
            'year.required'     => 'Tahun rilis wajib diisi.',
            'genre_id.required' => 'Genre film wajib dipilih.',
            'poster_file.image' => 'File poster harus berupa gambar (jpg, png, webp).',
            'poster_file.max'   => 'Ukuran poster maksimal 2MB.',
        ]);

        $posterPath = 'images/m1.jpg'; // default fallback

        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/posters');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $posterPath = 'uploads/posters/' . $filename;
        } elseif ($request->filled('poster_url')) {
            $posterPath = ltrim($request->input('poster_url'), '/');
        }

        $film = Film::create([
            'title'     => $request->title,
            'sinopsis'  => $request->sinopsis,
            'year'      => $request->year,
            'genre_id'  => $request->genre_id,
            'duration'  => $request->duration,
            'rating'    => $request->rating,
            'poster'    => $posterPath,
        ]);

        // Attach casts (many-to-many via perans)
        if ($request->has('casts') && is_array($request->input('casts'))) {
            foreach ($request->input('casts') as $castItem) {
                if (!empty($castItem['cast_id'])) {
                    Peran::create([
                        'film_id' => $film->id,
                        'cast_id' => $castItem['cast_id'],
                        'actor'   => $castItem['actor'] ?? '-',
                    ]);
                }
            }
        }

        return redirect()->route('admin.films.index')
            ->with('success', 'Film "' . $film->title . '" berhasil ditambahkan!');
    }

    /**
     * Display the specified film.
     */
    public function show(Film $film)
    {
        $film->load(['genre', 'actors', 'kritiks.user']);

        return view('admin.films.show', compact('film'));
    }

    /**
     * Show the form for editing the specified film.
     */
    public function edit(Film $film)
    {
        $genres = Genre::orderBy('name')->get();
        $casts = Cast::orderBy('name')->get();
        $film->load('perans.cast');

        return view('admin.films.edit', compact('film', 'genres', 'casts'));
    }

    /**
     * Update the specified film in storage.
     */
    public function update(Request $request, Film $film)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'sinopsis'    => ['required', 'string'],
            'year'        => ['required', 'integer', 'min:1880', 'max:' . (date('Y') + 5)],
            'genre_id'    => ['required', 'exists:genres,id'],
            'duration'    => ['nullable', 'integer', 'min:1', 'max:1000'],
            'rating'      => ['nullable', 'numeric', 'min:0', 'max:10'],
            'poster_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'poster_url'  => ['nullable', 'string', 'max:500'],
        ], [
            'title.required'    => 'Judul film wajib diisi.',
            'sinopsis.required' => 'Deskripsi/sinopsis film wajib diisi.',
            'year.required'     => 'Tahun rilis wajib diisi.',
            'genre_id.required' => 'Genre film wajib dipilih.',
            'poster_file.image' => 'File poster harus berupa gambar (jpg, png, webp).',
            'poster_file.max'   => 'Ukuran poster maksimal 2MB.',
        ]);

        $posterPath = $film->poster;

        if ($request->hasFile('poster_file')) {
            // Delete old uploaded poster if it is in uploads/
            if ($film->poster && str_starts_with($film->poster, 'uploads/posters/')) {
                $oldFile = public_path($film->poster);
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $file = $request->file('poster_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/posters');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $posterPath = 'uploads/posters/' . $filename;
        } elseif ($request->filled('poster_url')) {
            $posterPath = ltrim($request->input('poster_url'), '/');
        }

        $film->update([
            'title'     => $request->title,
            'sinopsis'  => $request->sinopsis,
            'year'      => $request->year,
            'genre_id'  => $request->genre_id,
            'duration'  => $request->duration,
            'rating'    => $request->rating,
            'poster'    => $posterPath,
        ]);

        // Sync casts via perans table
        $film->perans()->delete();
        if ($request->has('casts') && is_array($request->input('casts'))) {
            foreach ($request->input('casts') as $castItem) {
                if (!empty($castItem['cast_id'])) {
                    Peran::create([
                        'film_id' => $film->id,
                        'cast_id' => $castItem['cast_id'],
                        'actor'   => $castItem['actor'] ?? '-',
                    ]);
                }
            }
        }

        return redirect()->route('admin.films.index')
            ->with('success', 'Film "' . $film->title . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified film from storage.
     */
    public function destroy(Film $film)
    {
        $title = $film->title;

        // Delete uploaded poster if custom
        if ($film->poster && str_starts_with($film->poster, 'uploads/posters/')) {
            $oldFile = public_path($film->poster);
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        }

        // Delete relations
        $film->perans()->delete();
        $film->kritiks()->delete();
        $film->delete();

        return redirect()->route('admin.films.index')
            ->with('success', 'Film "' . $title . '" berhasil dihapus!');
    }
}
