<?php

namespace App\Helpers;

class ChordFormatter
{
    public static function format(string $text): string
    {
        $escaped = e($text);
        return preg_replace('/\[([^\]]+)\]/', '<span class="chord">[$1]</span>', $escaped);
    }
}
