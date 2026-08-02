<?php

namespace App\Helpers;

class ChordFormatter
{
    // Existing methods...

    /**
     * Convert to chord‑sheet style with formatting tags support.
     */
    public static function toChordSheet(string $text): string
    {
        // Normalize blank lines
        $text = preg_replace('/\n\s*\n/', "\n", $text);

        $lines = explode("\n", $text);
        $output = [];
        $lastChordLine = null;

        foreach ($lines as $line) {
            $line = rtrim($line);

            if (trim($line) === '') {
                continue;
            }

            // Section header
            if (preg_match('/^\[([^\]]+)\]$/', $line, $matches)) {
                if ($lastChordLine !== null) {
                    $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
                    $lastChordLine = null;
                }
                $output[] = '<div class="section-heading">' . e($matches[1]) . '</div>';
                continue;
            }

            // Chord line detection (has brackets)
            if (preg_match('/\[[^\]]+\]/', $line)) {
                $chordLine = preg_replace('/\[([^\]]+)\]/', '$1', $line);
                $lastChordLine = $chordLine;
            } else {
                // Lyric line – apply formatting tags
                $formatted = self::applyFormatting($line);
                if ($lastChordLine !== null) {
                    $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
                    $output[] = '<div class="lyric-line">' . $formatted . '</div>';
                    $lastChordLine = null;
                } else {
                    $output[] = '<div class="lyric-line">' . $formatted . '</div>';
                }
            }
        }

        if ($lastChordLine !== null) {
            $output[] = '<div class="chord-line">' . e($lastChordLine) . '</div>';
        }

        return implode("\n", $output);
    }

    /**
     * Apply formatting tags to a lyric line.
     * Tags: [b]bold[/b], [i]italic[/i], [u]underline[/u],
     * [center]text[/center], [left]text[/left], [right]text[/right]
     */
    private static function applyFormatting(string $line): string
    {
        $line = e($line); // Escape HTML

        // Bold
        $line = preg_replace('/\[b\](.*?)\[\/b\]/i', '<strong>$1</strong>', $line);
        // Italic
        $line = preg_replace('/\[i\](.*?)\[\/i\]/i', '<em>$1</em>', $line);
        // Underline
        $line = preg_replace('/\[u\](.*?)\[\/u\]/i', '<u>$1</u>', $line);
        // Alignment – wrap the whole line in a div with text-align
        $line = preg_replace('/\[center\](.*?)\[\/center\]/i', '<div style="text-align: center;">$1</div>', $line);
        $line = preg_replace('/\[left\](.*?)\[\/left\]/i', '<div style="text-align: left;">$1</div>', $line);
        $line = preg_replace('/\[right\](.*?)\[\/right\]/i', '<div style="text-align: right;">$1</div>', $line);

        return $line;
    }
}
