<?php

namespace App\Helpers;

class ChordFormatter
{
    /**
     * Original method – keeps [Chord] inline as a span.
     */
    public static function format(string $text): string
    {
        $escaped = e($text);
        return preg_replace('/\[([^\]]+)\]/', '<span class="chord">[$1]</span>', $escaped);
    }

    /**
     * Normalize chord root to standard spelling.
     * e.g. B# → C, E# → F, Cb → B, Fb → E
     * Handles suffixes like m, 7, sus, etc.
     */
    protected static function normalizeChord(string $chord): string
    {
        // Match root (one or two letters, possibly with # or b)
        if (preg_match('/^([A-Ga-g][#b]?)(.*)$/', $chord, $matches)) {
            $root = $matches[1];
            $suffix = $matches[2];

            // Mapping of sharp/flat roots to natural equivalents
            $map = [
                'B#' => 'C',
                'E#' => 'F',
                'Cb' => 'B',
                'Fb' => 'E',
                // Double sharps/flats (rare but handle)
                'B##' => 'C#',
                'E##' => 'F#',
                'Cbb' => 'Bb',
                'Fbb' => 'Eb',
            ];

            // Preserve case: if original root was uppercase, keep result uppercase
            $isUpper = ctype_upper($root[0]);
            $normalized = $map[$root] ?? $root;
            if ($isUpper) {
                $normalized = strtoupper($normalized);
            } else {
                $normalized = strtolower($normalized);
            }

            return $normalized . $suffix;
        }

        return $chord;
    }

    /**
     * Convert to chord‑sheet style (chords above lyrics).
     * Removes brackets, aligns chords above corresponding lyric lines.
     * Skips empty or whitespace‑only lines.
     */
    public static function toChordSheet(string $text): string
    {
        // Normalize: remove blank lines
        $text = preg_replace('/\n\s*\n/', "\n", $text);

        $lines = explode("\n", $text);
        $output = [];
        $lastChordLine = null;

        foreach ($lines as $line) {
            $line = rtrim($line);

            if (trim($line) === '') {
                continue;
            }

            // Section header like [Intro], [Verse]
            if (preg_match('/^\[([^\]]+)\]$/', $line, $matches)) {
                if ($lastChordLine !== null) {
                    $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
                    $lastChordLine = null;
                }
                $output[] = '<div class="section-heading">' . e($matches[1]) . '</div>';
                continue;
            }

            // Check for chord line (contains brackets)
            if (preg_match('/\[[^\]]+\]/', $line)) {
                // Remove brackets and normalize each chord token
                $chordLine = preg_replace('/\[([^\]]+)\]/', '$1', $line);
                // Normalize each chord in the line (split by spaces, process, rejoin)
                $tokens = explode(' ', $chordLine);
                $normalizedTokens = array_map([self::class, 'normalizeChord'], $tokens);
                $lastChordLine = implode(' ', $normalizedTokens);
            } else {
                // Lyric line
                if ($lastChordLine !== null) {
                    $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
                    $output[] = '<div class="lyric-line">' . e($line) . '</div>';
                    $lastChordLine = null;
                } else {
                    $output[] = '<div class="lyric-line">' . e($line) . '</div>';
                }
            }
        }

        if ($lastChordLine !== null) {
            $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
        }

        return implode("\n", $output);
    }
}
