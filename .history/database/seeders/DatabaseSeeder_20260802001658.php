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
        // ----- 1. Create or get the "Pop" collection -----
        $pop = Collection::firstOrCreate(
            ['slug' => 'pop'],
            [
                'name' => 'Pop',
                'description' => 'Popular music',
                'color' => '#2ecc71'
            ]
        );

        // ----- 2. Create or get the default user -----
        $user = User::firstOrCreate(
            ['email' => 'lamamanish234@gmail.com'],
            [
                'name' => 'Manish Lama',
                'password' => Hash::make('manish@@123')
            ]
        );

        // ----- 3. Create or get the "Komal" song -----
        $song = Song::firstOrCreate(
            [
                'title' => 'Komal',
                'artist' => 'Sabin Rai'
            ],
            [
                'album' => 'Love Songs',
                'year' => 2024,
                'genre' => 'Pop',
                'key' => 'B'
            ]
        );

        // Attach to the "Pop" collection (if not already attached)
        if (!$song->collections()->where('collection_id', $pop->id)->exists()) {
            $song->collections()->attach($pop);
        }

        // ----- 4. Add lyric sections (only if they don't exist) -----
        // We'll check if any lyric exists for this song – if not, add all.
        if ($song->lyrics()->count() === 0) {
            // Intro
            Lyric::create([
                'song_id' => $song->id,
                'section' => 'Intro',
                'content' => "[Intro]\n[B]          [D#m]   [E]\nKomal tyo timro badan ma\n[F#]        [B]\nDhalna man lagyo\n[D#m]             [E]     [F#]\nEkchin lai sara bhulera\n[C#m]      [E]          [F#]\nHarauna malai ischa jagyo\n[B]          [D#m]   [E]\nKhi kadam bhaye pani\n[F#]        [B]\nHidna man lagyo\n[D#m]        [E]        [F#]\nTimi sanga gahirai ma\n[C#m]      [E]          [F#]\nDubi herna ischa jagyo o ohaha"
            ]);

            // Verse
            Lyric::create([
                'song_id' => $song->id,
                'section' => 'Verse',
                'content' => "[Verse]\n      [E]                   [B]\nTimro agaman he mero jiwan ma\n              [F#]\nBeglai haso chadyo\n           [C#m]      [E]              [F#]   [E]\nHera mero ankha ma ispasta dekhne chau o yeah\n[B]\nTimro nam"
            ]);

            // Chorus
            Lyric::create([
                'song_id' => $song->id,
                'section' => 'Chorus',
                'content' => "[Chorus]\n[B]          [D#m]   [E]\nSuni dine daiba pani\n[F#]        [B]\nbairo chainan hola\n[D#m]             [E]     [F#]\nKasam cha mero timilai\n[C#m]      [E]          [F#]\nSath kahile chodne chuina\n[B]          [D#m]   [E]\nMalai hasau ya ruwau\n[F#]        [B]\nFarak parne chaina\n[D#m]             [E]     [F#]\nChali sake pahilo kadam\n[C#m]      [E]          [F#]\nBachujenlai harne chaina\n[E]                   [B]                     [F#]\nTimro maya ma dubeko chu yeti saro ki ekchin nadekhda\n           [C#m]      [E]              [F#]      [E]\nSara birano lagcha parichit thau pani    o yeah\n[B]\nTimi bina"
            ]);

            // Outro
            Lyric::create([
                'song_id' => $song->id,
                'section' => 'Outro',
                'content' => "[Outro]\n[Bm]                [D]        [A]         [E]\nSukha hos ya dhukha timro sath paune chu\n[Bm]                  [D]       [A]\nJahile timilai mero sath chahincha\n[Bm]                [D]        [A]         [E]\nTimilai jasto aru lai ma maya garna sakdina\n[G]             [A]     [F#m]\nMero yehi ho antim sabda\n[Bm]                [D]        [A]         [E]\nSukha hos ya dhukha timro sath paune chu\n[Bm]                  [D]       [A]\nJahile timilai mero sath chahincha\n[Bm]                [D]        [A]         [E]\nTimilai jasto aru lai ma maya garna sakdina\n[G]             [A]     [F#m]\nMero yehi ho antim sabda\n[E]                   [B]                     [F#]\nTimro maya ma dubeko chu yeti saro ki ekchin nadekhda\n           [C#m]      [E]              [F#]      [E]\nSara birano lagcha parichit thau pani    o yeah\n[B]\nTimi bina"
            ]);

            $this->command->info('✅ Komal lyrics added.');
        } else {
            $this->command->info('ℹ️ Komal lyrics already exist – skipped.');
        }

        // ----- Output stats -----
        $this->command->info('✅ Seeded:');
        $this->command->info('   - 1 collection (Pop)');
        $this->command->info('   - 1 song (Komal)');
        $this->command->info('   - ' . $song->lyrics()->count() . ' lyric blocks');
        $this->command->info('   - 1 user (' . $user->email . ')');
    }
}
