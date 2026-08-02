<?php

namespace Database\Seeders;

use App\Models\Song;
use App\Models\Collection;
use App\Models\Lyric;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $bhajan = Collection::create(['name' => 'Bhajan', 'slug' => 'bhajan', 'color' => '#e74c3c']);
        $chorus = Collection::create(['name' => 'Chorus', 'slug' => 'chorus', 'color' => '#3498db']);

        $song = Song::create([
            'title' => 'Om Jai Jagdish Hare',
            'artist' => 'Traditional',
            'genre' => 'Bhajan',
            'key' => 'G'
        ]);
        $song->collections()->attach($bhajan);

        Lyric::create([
            'song_id' => $song->id,
            'section' => 'Verse 1',
            'content' => "[G] Om Jai Jagdish [C] Hare\n[G] Swami Jai Jagdish [D] Hare"
        ]);

        User::create([
            'name' => 'Manish',
            'email' => 'test@example.com',
        ]);
    }
}
