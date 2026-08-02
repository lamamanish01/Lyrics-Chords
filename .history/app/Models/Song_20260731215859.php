<?php

namespace App\Models;

use App\Models\Lyric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'artist', 'album', 'year', 'genre', 'key'];

    public function lyrics()
    {
        return $this->hasMany(Lyric::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }
}
