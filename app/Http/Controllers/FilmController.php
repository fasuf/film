<?php

namespace App\Http\Controllers;

use App\Models\{
    Film,
    Genre,
    Kritik,
    Peran,
};
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * Display a listing of all movies with optional search & pagination.
     */
    public function movies(Request $request)
    {
        $genreFilm = null;
        $query = Film::select('id', 'title', 'poster', 'year');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhere('sinopsis', 'like', "%{$search}%");
            });
        }

        $films = $query->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->paginate(18)
            ->withQueryString();

        return view('components.movies', compact('films', 'genreFilm'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilmRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film)
    {
        $film->load('genre');

        $filmByGenre = Film::select('id', 'title', 'poster', 'year', 'sinopsis')
            ->where('genre_id', '=', $film->genre_id)
            ->where('id', '!=', $film->id)
            ->orderBy('created_at', 'asc')
            ->limit(7)
            ->get();

        $filmByRelease = Film::select('id', 'title', 'poster', 'year')
            ->where('year', '=', Carbon::now()->format('Y'))
            ->where('id', '!=', $film->id)
            ->orderBy('created_at', 'asc')
            ->limit(9)
            ->get();

        $comments = Kritik::with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('film_id', '=', $film->id)
            ->latest('created_at')
            ->get();

        // Eager load cast relation to prevent N+1 queries
        $perans = Peran::with('cast')
            ->where('film_id', '=', $film->id)
            ->get();

        return view('components.movie-show', compact('film', 'filmByGenre', 'filmByRelease', 'comments', 'perans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Film $film)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilmRequest $request, Film $film)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        //
    }

    public function movieHome()
    {
        $films = Film::select('id', 'title', 'poster', 'year')
            ->orderBy('created_at', 'asc')
            ->limit(9)->get();
        $filmFutured = Film::select('id', 'title', 'poster', 'year')
            ->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->limit(6)->get();
        $filmRecentAdded = Film::select('id', 'title', 'poster', 'year')
            ->orderByDesc('created_at', 'asc')
            ->limit(6)->get();
        $filmSlidey = Film::select('title', 'poster', 'sinopsis')
            ->orderByDesc('year')
            ->orderBy('created_at', 'asc')
            ->limit(6)->get();

        return view('components.home', compact('filmSlidey', 'films', 'filmFutured', 'filmRecentAdded'));
    }

    /**
     * Display movies filtered by genre (by ID or by name/slug).
     */
    public function moviesByGenre($genre)
    {
        $genreClean = str_replace('-', ' ', $genre);

        // Search by ID or name (case-insensitive)
        $genreFilm = Genre::where('id', $genre)
            ->orWhere('name', 'like', $genre)
            ->orWhere('name', 'like', $genreClean)
            ->first();

        if ($genreFilm) {
            $films = Film::select('id', 'title', 'poster', 'year')
                ->where('genre_id', $genreFilm->id)
                ->orderByDesc('year')
                ->orderBy('created_at', 'asc')
                ->paginate(18);
        } else {
            // Genre not found: do not 404! Return empty paginator with pseudo genre object
            $genreFilm = new Genre(['name' => ucfirst($genreClean)]);
            $films = Film::whereRaw('1 = 0')->paginate(18);
        }

        return view('components.movies', compact('films', 'genreFilm'));
    }

    /**
     * Display FAQ static page with accordion.
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Apa itu platform OneMovies?',
                'answer'   => 'OneMovies adalah platform katalog film digital terlengkap yang menyajikan informasi detail sinopsis, tahun rilis, genre, jajaran aktor/cast pemeran, serta ulasan dan rating dari para penikmat film.'
            ],
            [
                'question' => 'Bagaimana cara mendaftar akun baru?',
                'answer'   => 'Klik tombol Login di pojok kanan atas halaman. Pilih tab pendaftaran, isi nama lengkap, email yang valid, serta password minimal 8 karakter. Setelah mendaftar, akun Anda akan otomatis aktif.'
            ],
            [
                'question' => 'Bagaimana cara memberikan komentar dan rating pada film?',
                'answer'   => 'Buka halaman detail film yang ingin Anda ulas. Pastikan Anda telah login ke akun Anda. Gulir ke bawah hingga bagian ulasan & komentar, pilih skor rating bintang (1-10), ketikkan tanggapan Anda di kolom yang tersedia, lalu klik tombol "KIRIM KOMENTAR".'
            ],
            [
                'question' => 'Apakah saya bisa mencari film berdasarkan genre tertentu?',
                'answer'   => 'Tentu saja! Anda dapat memilih menu dropdown "Genres" di bar navigasi atas atau menu cepat di bagian footer (Action, Adventure, Comedy, dsb.) untuk melihat seluruh daftar film yang difilter khusus genre tersebut.'
            ],
            [
                'question' => 'Bagaimana cara mencari film berdasarkan judul?',
                'answer'   => 'Gunakan kotak pencarian yang terletak di header navigasi bagian atas. Masukkan kata kunci atau judul film yang dicari, lalu klik tombol "Go". Hasil film yang sesuai akan segera ditampilkan.'
            ],
            [
                'question' => 'Apakah ulasan atau komentar saya dapat dihapus jika melanggar ketentuan?',
                'answer'   => 'Ya, tim Administrator OneMovies secara berkala memantau dan memoderasi seluruh komentar pengguna untuk menjaga kenyamanan komunitas. Komentar yang mengandung ujaran kebencian, spam, atau kata-kata tidak pantas dapat dihapus oleh pihak admin.'
            ],
            [
                'question' => 'Apakah layanan katalog film ini berbayar?',
                'answer'   => 'Tidak, seluruh informasi katalog film, sinopsis, daftar pemeran, dan fitur ulasan komunitas di OneMovies dapat diakses secara gratis oleh seluruh pengunjung.'
            ],
        ];

        return view('components.faq', compact('faqs'));
    }
}
