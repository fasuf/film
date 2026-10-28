<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kritik extends Model
{
    use HasFactory;

    protected $table = 'kritiks';
    protected $primaryKey = 'id';
    protected $fillable = [
        'comment',
        'rating',
        'film_id',
        'user_id',
    ];

    /**
     * Kritik belongs to a user.
     * Falls back to "Anonim" if user is deleted or missing.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'Anonim',
        ]);
    }

    /**
     * Accessor for user display name with fallback.
     */
    public function getUserNameAttribute(): string
    {
        return !empty($this->user?->name) ? $this->user->name : 'Anonim';
    }

    /**
     * Kritik belongs to a film.
     */
    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }
}
