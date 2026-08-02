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
        // Collection
        $pop = Collection::firstOrCreate(
            ['slug' => 'pop'],
            ['name' => 'Pop', 'description' => 'Popular music', 'color' => '#2ecc71']
        );

        // User
        User::firstOrCreate(
            ['email' => 'lamamanish234@gmail.com'],
            ['name' => 'Manish Lama', 'password' => Hash::make('manish@@123')]
        );

        // Song
        $song = Song::create([
            'title' => 'Komal',
            'artist' => 'Your Artist Name',
            'album' => 'Love Songs',
            'year' => 2024,
            'genre' => 'Pop',
            'key' => 'B'
        ]);
        $song->collections()->attach($pop);

        // Lyrics (with both Romanized and Nepali)
        Lyric::create([
            'song_id' => $song->id,
            'section' => 'Intro',
            'content' => "[Intro]\n[B]          [D#m]   [E]\nKomal tyo timro badan ma\n[F#]        [B]\nDhalna man lagyo\n[D#m]             [E]     [F#]\nEkchin lai sara bhulera\n[C#m]      [E]          [F#]\nHarauna malai ischa jagyo\n[B]          [D#m]   [E]\nKhi kadam bhaye pani\n[F#]        [B]\nHidna man lagyo\n[D#m]        [E]        [F#]\nTimi sanga gahirai ma\n[C#m]      [E]          [F#]\nDubi herna ischa jagyo o ohaha",
            'content_nepali' => "[Intro]\n[B]          [D#m]   [E]\nकोमल त्यो तिम्रो बदन मा\n[F#]        [B]\nढल्न मन लाग्यो\n[D#m]             [E]     [F#]\nएकछिन लाई सारा भुलेर\n[C#m]      [E]          [F#]\nहराउन मलाई इच्छा जाग्यो\n[B]          [D#m]   [E]\nखि कदम भए पनि\n[F#]        [B]\nहिड्न मन लाग्यो\n[D#m]        [E]        [F#]\nतिमी सँग गहिराइ मा\n[C#m]      [E]          [F#]\nडुबि हेर्न इच्छा जाग्यो ओ ओहाह"
        ]);

        Lyric::create([
            'song_id' => $song->id,
            'section' => 'Verse',
            'content' => "[Verse]\n      [E]                   [B]\nTimro agaman he mero jiwan ma\n              [F#]\nBeglai haso chadyo\n           [C#m]      [E]              [F#]   [E]\nHera mero ankha ma ispasta dekhne chau o yeah\n[B]\nTimro nam",
            'content_nepali' => "[Verse]\n      [E]                   [B]\nतिम्रो आगमन हे मेरो जीवन मा\n              [F#]\nबेग्लै हाँसो छाड्यो\n           [C#m]      [E]              [F#]   [E]\nहेर मेरो आँखा मा इस्पास्ता देख्ने छौ ओ yeah\n[B]\nतिम्रो नाम"
        ]);

        Lyric::create([
            'song_id' => $song->id,
            'section' => 'Chorus',
            'content' => "[Chorus]\n[B]          [D#m]   [E]\nSuni dine daiba pani\n[F#]        [B]\nbairo chainan hola\n[D#m]             [E]     [F#]\nKasam cha mero timilai\n[C#m]      [E]          [F#]\nSath kahile chodne chuina\n[B]          [D#m]   [E]\nMalai hasau ya ruwau\n[F#]        [B]\nFarak parne chaina\n[D#m]             [E]     [F#]\nChali sake pahilo kadam\n[C#m]      [E]          [F#]\nBachujenlai harne chaina",
            'content_nepali' => "[Chorus]\n[B]          [D#m]   [E]\nसुनी दिने दैबा पनि\n[F#]        [B]\nबैरो चैनन होला\n[D#m]             [E]     [F#]\nकसम छ मेरो तिमीलाई\n[C#m]      [E]          [F#]\nसाथ कहिले छोड्ने छैन\n[B]          [D#m]   [E]\nमलाई हसाउ या रुवाउ\n[F#]        [B]\nफरक पर्ने छैन\n[D#m]             [E]     [F#]\nछाली सके पहिलो कदम\n[C#m]      [E]          [F#]\nबचउँझेलाई हार्ने छैन"
        ]);

        Lyric::create([
            'song_id' => $song->id,
            'section' => 'Outro',
            'content' => "[Outro]\n[Bm]                [D]        [A]         [E]\nSukha hos ya dhukha timro sath paune chu\n[Bm]                  [D]       [A]\nJahile timilai mero sath chahincha\n[Bm]                [D]        [A]         [E]\nTimilai jasto aru lai ma maya garna sakdina\n[G]             [A]     [F#m]\nMero yehi ho antim sabda",
            'content_nepali' => "[Outro]\n[Bm]                [D]        [A]         [E]\nसुख होस या दुख तिम्रो साथ पाउने छु\n[Bm]                  [D]       [A]\nजहिले तिमीलाई मेरो साथ चाहिन्छ\n[Bm]                [D]        [A]         [E]\nतिमीलाई जस्तो अरु लाई म माया गर्न सक्दिन\n[G]             [A]     [F#m]\nमेरो यहि हो अन्तिम सब्द"
        ]);

        $this->command->info('✅ Komal song seeded with both Romanized and Nepali lyrics!');
    }
}
