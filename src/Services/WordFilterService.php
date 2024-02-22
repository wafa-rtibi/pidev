<?php

namespace App\Service;

class WordFilterService
{
    private $badWords = ['bad', 'words', 'wafa', 'hello']; // Liste de mots inappropriés

    public function filterWords(string $text): string
    {
        foreach ($this->badWords as $badWord) {
            $text = preg_replace('/\b' . preg_quote($badWord, '/') . '\b/i', str_repeat('*', strlen($badWord)), $text);
        }
        return $text;
    }
}
