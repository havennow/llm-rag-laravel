<?php

namespace App\Services;

final class ChunkerService
{
    public function split(
        string $text,
        int $maxCharacters = 3000,
        int $overlap = 500,
    ): array {
        $text = $this->normalize($text);

        if ($text === '') {
            return [];
        }

        $paragraphs = preg_split(
            '/\n{2,}/',
            $text,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $chunks = [];
        $current = '';

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);

            if ($paragraph === '') {
                continue;
            }

            $candidate = $current === ''
                ? $paragraph
                : $current . "\n\n" . $paragraph;

            if (mb_strlen($candidate) <= $maxCharacters) {
                $current = $candidate;
                continue;
            }

            if ($current !== '') {
                $chunks[] = $current;
            }

            $overlapText = $this->getOverlap(
                $current,
                $overlap
            );

            $current = $overlapText !== ''
                ? $overlapText . "\n\n" . $paragraph
                : $paragraph;
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks;
    }

    private function normalize(string $text): string
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        // Remove espaços excessivos
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // Normaliza linhas vazias
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }

    private function getOverlap(
        string $text,
        int $characters
    ): string {
        if ($text === '') {
            return '';
        }

        return mb_substr(
            $text,
            max(0, mb_strlen($text) - $characters)
        );
    }
}
