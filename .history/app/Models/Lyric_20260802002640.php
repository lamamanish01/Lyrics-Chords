<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lyric extends Model
{
    use HasFactory;

    protected $fillable = ['song_id', 'content', content_nepali 'section'];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
