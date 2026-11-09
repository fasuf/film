<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $table = 'films';

    protected $fillable = [
        'title',
        'sinopsis',
        'year',
        'poster',
        'genre_id',
        'duration',
        'rating',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year'     => 'integer',
            'duration' => 'integer',
            'rating'   => 'float',
        ];
    }

    public function getPosterAttribute($value): string
    {
        if (empty($value)) {
            return 'template/images/m1.jpg';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');

        if (str_starts_with($clean, 'storage/images/')) {
            return 'template/images/' . substr($clean, strlen('storage/images/'));
        }

        if (str_starts_with($clean, 'images/')) {
            return 'template/images/' . substr($clean, strlen('images/'));
        }

        return $clean;
    }

    public function getPosterUrlAttribute(): string
    {
        $poster = $this->poster;
        if (str_starts_with($poster, 'http://') || str_starts_with($poster, 'https://')) {
            return $poster;
        }
        return asset($poster);
    }

    /**
     * Film belongs to a genre.
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    /**
     * Film has many actors / casts through perans pivot.
     * Note: Named 'actors' / 'castMembers' to prevent collision with Laravel 11's Model::casts() method.
     */
    public function actors()
    {
        return $this->belongsToMany(Cast::class, 'perans', 'film_id', 'cast_id')
            ->withPivot('id', 'actor')
            ->withTimestamps();
    }

    public function castMembers()
    {
        return $this->actors();
    }

    /**
     * Accessor for $film->casts
     */
    public function getCastsAttribute()
    {
        return $this->actors;
    }

    /**
     * Film perans.
     */
    public function perans()
    {
        return $this->hasMany(Peran::class, 'film_id');
    }

    /**
     * Film reviews/critics.
     */
    public function kritiks()
    {
        return $this->hasMany(Kritik::class, 'film_id');
    }
}
