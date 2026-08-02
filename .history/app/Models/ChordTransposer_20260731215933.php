<?php

namespace App\Services;

class ChordTransposer
{
    protected static $chromatic = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];

    public static function transpose(string $text, int $halfSteps): string
    {
        if ($halfSteps === 0) return $text;

        $pattern = '/\b([A-G])([#b]?)([a-z0-9]+?)?(?:\/([A-G])([#b]?))?\b/ix';

        return preg_replace_callback($pattern, function ($matches) use ($halfSteps) {
            $root = $matches[1];
            $accidental = $matches[2] ?? '';
            $suffix = $matches[3] ?? '';
            $bassRoot = $matches[4] ?? '';
            $bassAccidental = $matches[5] ?? '';

            $transposedRoot = self::transposeNote($root . $accidental, $halfSteps);
            $transposedBass = '';
            if ($bassRoot) {
                $transposedBass = '/' . self::transposeNote($bassRoot . $bassAccidental, $halfSteps);
            }

            return $transposedRoot . $suffix . $transposedBass;
        }, $text);
    }

    protected static function transposeNote(string $note, int $halfSteps): string
    {
        $flatToSharp = ['Db' => 'C#', 'Eb' => 'D#', 'Gb' => 'F#', 'Ab' => 'G#', 'Bb' => 'A#'];
        $original = $note;
        if (isset($flatToSharp[$note])) $note = $flatToSharp[$note];

        $index = array_search($note, self::$chromatic);
        if ($index === false) return $original;

        $newIndex = ($index + $halfSteps) % 12;
        if ($newIndex < 0) $newIndex += 12;

        return self::$chromatic[$newIndex];
    }
}
