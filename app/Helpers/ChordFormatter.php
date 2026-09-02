<?php

namespace App\Helpers;

class ChordFormatter
{
    /**
     * Original method – keeps [Chord] inline as a span (used elsewhere).
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
     * Convert to chord‑sheet style (chords above lyrics) – FIXED version.
     * Preserves all spacing using a <pre> block.
     * Removes brackets, aligns chords above corresponding lyric lines.
     * Skips empty or whitespace‑only lines.
     */
    public static function toChordSheet(string $text): string
    {
        // Normalize: remove blank lines
        $text = preg_replace('/\n\s*\n/', "\n", $text);

        $lines = explode("\n", $text);
        $outputLines = [];
        $lastChordLine = null;

        foreach ($lines as $line) {
            $line = rtrim($line);

            if (trim($line) === '') {
                continue;
            }

            // Section header like [Intro], [Verse] – output as separate block
            if (preg_match('/^\[([^\]]+)\]$/', $line, $matches)) {
                // If we had a pending chord line, push it before the heading
                if ($lastChordLine !== null) {
                    $outputLines[] = $lastChordLine; // chord line
                    $lastChordLine = null;
                }
                $outputLines[] = '---SECTION---' . $matches[1]; // marker for later
                continue;
            }

            // Check for chord line (contains brackets)
            if (preg_match('/\[[^\]]+\]/', $line)) {
                // Remove brackets and normalize each chord token
                $chordLine = preg_replace('/\[([^\]]+)\]/', '$1', $line);
                $tokens = explode(' ', $chordLine);
                $normalizedTokens = array_map([self::class, 'normalizeChord'], $tokens);
                $lastChordLine = implode(' ', $normalizedTokens);
            } else {
                // Lyric line
                if ($lastChordLine !== null) {
                    // Output chord line, then lyric line
                    $outputLines[] = $lastChordLine;
                    $outputLines[] = $line;
                    $lastChordLine = null;
                } else {
                    // Standalone lyric line (no preceding chord line)
                    $outputLines[] = $line;
                }
            }
        }

        // If there's a leftover chord line (no lyric after), add it
        if ($lastChordLine !== null) {
            $outputLines[] = $lastChordLine;
        }

        // Now convert to HTML: wrap in <pre> for proper spacing
        $html = '';
        $inPre = false;
        foreach ($outputLines as $line) {
            if (strpos($line, '---SECTION---') === 0) {
                // Close pre if open
                if ($inPre) {
                    $html .= '</pre>';
                    $inPre = false;
                }
                $heading = substr($line, 13); // remove marker
                $html .= '<div class="section-heading">' . e($heading) . '</div>';
            } else {
                // This is a chord or lyric line
                if (!$inPre) {
                    $html .= '<pre class="chord-sheet-pre">';
                    $inPre = true;
                }
                $html .= e($line) . "\n";
            }
        }
        if ($inPre) {
            $html .= '</pre>';
        }

        return $html;
    }
}
