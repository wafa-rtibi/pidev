<?php

namespace App\Service;

/**
 * Service pour filtrer les mots inappropriés dans une chaîne de texte.
 */
class BadWordsReclamationService
{
    /**
     * Liste des mots inappropriés à filtrer.
     *
     * @var array
     */
    private $badWords = ['trocy', 'normal', 'merci', 'okay'];

    /**
     * Filtrer les mots inappropriés dans le texte donné.
     *
     * @param string $text Le texte à filtrer.
     * @return string Le texte filtré.
     */
    public function filterWords(string $text): string
    {
        foreach ($this->badWords as $badWord) {
            // Remplacer chaque occurrence du mot inapproprié par des astérisques de même longueur.
            $text = preg_replace('/\b' . preg_quote($badWord, '/') . '\b/i', str_repeat('*', strlen($badWord)), $text);
        }
        return $text;
    }
}
