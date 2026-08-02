<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Lyric;
use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ----- 1. Create Collections -----
        $bhajan = Collection::create([
            'name' => 'Bhajan',
            'slug' => 'bhajan',
            'description' => 'Devotional songs',
            'color' => '#e74c3c'
        ]);

        $chorus = Collection::create([
            'name' => 'Chorus',
            'slug' => 'chorus',
            'description' => 'Choral songs and group singing',
            'color' => '#3498db'
        ]);

        $pop = Collection::create([
            'name' => 'Pop',
            'slug' => 'pop',
            'description' => 'Popular music',
            'color' => '#2ecc71'
        ]);

        $classic = Collection::create([
            'name' => 'Classic',
            'slug' => 'classic',
            'description' => 'Classic songs',
            'color' => '#f39c12'
        ]);

        // ----- 2. Create Songs with Lyrics -----

        // Song 1: Om Jai Jagdish Hare (Bhajan)
        $song1 = Song::create([
            'title' => 'Om Jai Jagdish Hare',
            'artist' => 'Traditional',
            'album' => 'Hindu Bhajans',
            'year' => null,
            'genre' => 'Bhajan',
            'key' => 'G'
        ]);
        $song1->collections()->attach($bhajan);

        Lyric::create([
            'song_id' => $song1->id,
            'section' => 'Intro',
            'content' => "[Intro]\n[G]                         [C]\nOm Jai Jagdish Hare\n[G]                         [D]\nSwami Jai Jagdish Hare"
        ]);

        Lyric::create([
            'song_id' => $song1->id,
            'section' => 'Verse 1',
            'content' => "[Verse 1]\n[G]                         [C]\nBhakt jano ke sankat\n[G]                         [D]\nKshan mein door kare\n[G]                         [C]\nJo dhyave phal pave\n[G]                         [D]\nDukh bin se mann hare"
        ]);

        Lyric::create([
            'song_id' => $song1->id,
            'section' => 'Chorus',
            'content' => "[Chorus]\n[G]                         [C]\nOm Jai Jagdish Hare\n[G]                         [D]\nSwami Jai Jagdish Hare"
        ]);

        // Song 2: Komal (Pop – Full version with all sections)
        $song2 = Song::create([
            'title' => 'Komal',
            'artist' => 'Your Artist Name',
            'album' => 'Love Songs',
            'year' => 2024,
            'genre' => 'Pop',
            'key' => 'B'
        ]);
        $song2->collections()->attach($pop);

        Lyric::create([
            'song_id' => $song2->id,
            'section' => 'Intro',
            'content' => "[Intro]\n[B]          [D#m]   [E]\nKomal tyo timro badan ma\n[F#]        [B]\nDhalna man lagyo\n[D#m]             [E]     [F#]\nEkchin lai sara bhulera\n[C#m]      [E]          [F#]\nHarauna malai ischa jagyo\n[B]          [D#m]   [E]\nKhi kadam bhaye pani\n[F#]        [B]\nHidna man lagyo\n[D#m]        [E]        [F#]\nTimi sanga gahirai ma\n[C#m]      [E]          [F#]\nDubi herna ischa jagyo o ohaha"
        ]);

        Lyric::create([
            'song_id' => $song2->id,
            'section' => 'Verse',
            'content' => "[Verse]\n      [E]                   [B]\nTimro agaman he mero jiwan ma\n              [F#]\nBeglai haso chadyo\n           [C#m]      [E]              [F#]   [E]\nHera mero ankha ma ispasta dekhne chau o yeah\n[B]\nTimro nam"
        ]);

        Lyric::create([
            'song_id' => $song2->id,
            'section' => 'Chorus',
            'content' => "[Chorus]\n[B]          [D#m]   [E]\nSuni dine daiba pani\n[F#]        [B]\nbairo chainan hola\n[D#m]             [E]     [F#]\nKasam cha mero timilai\n[C#m]      [E]          [F#]\nSath kahile chodne chuina\n[B]          [D#m]   [E]\nMalai hasau ya ruwau\n[F#]        [B]\nFarak parne chaina\n[D#m]             [E]     [F#]\nChali sake pahilo kadam\n[C#m]      [E]          [F#]\nBachujenlai harne chaina\n[E]                   [B]                     [F#]\nTimro maya ma dubeko chu yeti saro ki ekchin nadekhda\n           [C#m]      [E]              [F#]      [E]\nSara birano lagcha parichit thau pani    o yeah\n[B]\nTimi bina"
        ]);

        Lyric::create([
            'song_id' => $song2->id,
            'section' => 'Outro',
            'content' => "[Outro]\n[Bm]                [D]        [A]         [E]\nSukha hos ya dhukha timro sath paune chu\n[Bm]                  [D]       [A]\nJahile timilai mero sath chahincha\n[Bm]                [D]        [A]         [E]\nTimilai jasto aru lai ma maya garna sakdina\n[G]             [A]     [F#m]\nMero yehi ho antim sabda\n[Bm]                [D]        [A]         [E]\nSukha hos ya dhukha timro sath paune chu\n[Bm]                  [D]       [A]\nJahile timilai mero sath chahincha\n[Bm]                [D]        [A]         [E]\nTimilai jasto aru lai ma maya garna sakdina\n[G]             [A]     [F#m]\nMero yehi ho antim sabda\n[E]                   [B]                     [F#]\nTimro maya ma dubeko chu yeti saro ki ekchin nadekhda\n           [C#m]      [E]              [F#]      [E]\nSara birano lagcha parichit thau pani    o yeah\n[B]\nTimi bina"
        ]);

        // Song 3: Amazing Grace (Chorus – English hymn)
        $song3 = Song::create([
            'title' => 'Amazing Grace',
            'artist' => 'John Newton',
            'album' => 'Hymns',
            'year' => 1779,
            'genre' => 'Hymn',
            'key' => 'G'
        ]);
        $song3->collections()->attach($chorus);

        Lyric::create([
            'song_id' => $song3->id,
            'section' => 'Verse 1',
            'content' => "[Verse 1]\n[G]               [C]          [G]\nAmazing grace! How sweet the sound\n[G]          [D]            [G]\nThat saved a wretch like me\n[G]               [C]          [G]\nI once was lost, but now am found\n[G]          [D]            [G]\nWas blind, but now I see"
        ]);

        Lyric::create([
            'song_id' => $song3->id,
            'section' => 'Verse 2',
            'content' => "[Verse 2]\n[G]               [C]          [G]\nThrough many dangers, toils and snares\n[G]          [D]            [G]\nI have already come\n[G]               [C]          [G]\nHis grace has brought me safe thus far\n[G]          [D]            [G]\nAnd grace will lead me home"
        ]);

        // Song 4: Raghupati Raghav Raja Ram (Bhajan)
        $song4 = Song::create([
            'title' => 'Raghupati Raghav Raja Ram',
            'artist' => 'Traditional',
            'album' => 'Hindu Bhajans',
            'year' => null,
            'genre' => 'Bhajan',
            'key' => 'C'
        ]);
        $song4->collections()->attach($bhajan);

        Lyric::create([
            'song_id' => $song4->id,
            'section' => 'Main',
            'content' => "[Main]\n[C]                         [F]\nRaghupati Raghav Raja Ram\n[C]                         [G]\nPatita Pavan Sita Ram\n[C]                         [F]\nSita Ram Jai Sita Ram\n[C]                         [G]\nBhaj Mana Pyare Sita Ram"
        ]);

        // Song 5: Jingle Bells (Chorus – Christmas)
        $song5 = Song::create([
            'title' => 'Jingle Bells',
            'artist' => 'James Lord Pierpont',
            'album' => 'Christmas Songs',
            'year' => 1857,
            'genre' => 'Christmas',
            'key' => 'G'
        ]);
        $song5->collections()->attach($chorus);

        Lyric::create([
            'song_id' => $song5->id,
            'section' => 'Chorus',
            'content' => "[Chorus]\n[G]                         [C]\nJingle bells, jingle bells\n[G]                         [D]\nJingle all the way\n[G]                         [C]\nOh what fun it is to ride\n[G]             [D]        [G]\nIn a one-horse open sleigh"
        ]);

        // ----- 3. Output stats -----
        $this->command->info('✅ Seeded:');
        $this->command->info('   - ' . Collection::count() . ' collections');
        $this->command->info('   - ' . Song::count() . ' songs');
        $this->command->info('   - ' . Lyric::count() . ' lyric blocks');
    },

    User::create([
            'name' => 'Manish Lama',
            'email' => 'lamamanish234@gmail.com',
            'password' => Hash::make('manish@@123')
        ]);
}
