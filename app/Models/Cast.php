<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    protected $table = 'casts';

    protected $fillable = [
        'name',
        'age',
        'bio',
    ];

    /**
     * Cast plays in many films.
     */
    public function films()
    {
        return $this->belongsToMany(Film::class, 'perans', 'cast_id', 'film_id')
            ->withPivot('id', 'actor')
            ->withTimestamps();
    }

    /**
     * Cast perans.
     */
    public function perans()
    {
        return $this->hasMany(Peran::class, 'cast_id');
    }
}
