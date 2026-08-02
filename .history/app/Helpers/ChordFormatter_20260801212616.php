<?php

namespace App\Helpers;

class ChordFormatter
{
    /**
     * Original method – keeps [Chord] inline as a span.
     * Used for legacy formatting if needed.
     */
    public static function format(string $text): string
    {
        $escaped = e($text);
        return preg_replace('/\[([^\]]+)\]/', '<span class="chord">[$1]</span>', $escaped);
    }

    /**
     * Convert to chord‑sheet style (chords above lyrics).
     * Removes brackets, aligns chords above corresponding lyric lines.
     * Skips empty lines to avoid extra spacing between chord and lyric lines.
     *
     * Example input:
     *   [Intro]
     *   [F#]               [B]          [F#]
     *   G#mazing grace! How sweet the sound
     *
     * Output:
     *   <div class="section-heading">Intro</div>
     *   <div class="chord-line">F#               B          F#</div>
     *   <div class="lyric-line">G#mazing grace! How sweet the sound</div>
     */
    public static function toChordSheet(string $text): string
    {
        $lines = explode("\n", $text);
        $output = [];
        $lastChordLine = null;

        foreach ($lines as $line) {
            $line = rtrim($line);

            // Skip completely empty lines – this removes the blank line between chords and lyrics
            if ($line === '') {
                continue;
            }

            // Section header like [Intro], [Verse] – display as heading without brackets
            if (preg_match('/^\[([^\]]+)\]$/', $line, $matches)) {
                // If there's a pending chord line, output it first
                if ($lastChordLine !== null) {
                    $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
                    $lastChordLine = null;
                }
                $output[] = '<div class="section-heading">' . e($matches[1]) . '</div>';
                continue;
            }

            // Check if line contains chord symbols (has brackets)
            if (preg_match('/\[[^\]]+\]/', $line)) {
                // Remove brackets and keep the chord symbols
                $chordLine = preg_replace('/\[([^\]]+)\]/', '$1', $line);
                $lastChordLine = $chordLine;
            } else {
                // Lyric line
                if ($lastChordLine !== null) {
                    // Output both lines: chord line then lyric line
                    $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
                    $output[] = '<div class="lyric-line">' . e($line) . '</div>';
                    $lastChordLine = null;
                } else {
                    // No chord line – just output the lyric
                    $output[] = '<div class="lyric-line">' . e($line) . '</div>';
                }
            }
        }

        // If there's a leftover chord line (e.g. at the end), output it alone
        if ($lastChordLine !== null) {
            $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
        }

        return implode("\n", $output);
    }
}
