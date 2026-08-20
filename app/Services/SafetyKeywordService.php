<?php

namespace App\Services;

class SafetyKeywordService
{
    public function containsEmergencyLanguage(?string $text): bool
    {
        $text = strtolower((string) $text);

        foreach ($this->emergencyPhrases() as $phrase) {
            if (str_contains($text, $phrase)) {
                return true;
            }
        }

        return false;
    }

    public function containsNegativeLanguage(?string $text): bool
    {
        $text = strtolower((string) $text);

        foreach ($this->negativePhrases() as $phrase) {
            if (str_contains($text, $phrase)) {
                return true;
            }
        }

        return false;
    }

    public function emergencyPhrases(): array
    {
        return [
            'suicide',
            'suicidal',
            'kill myself',
            'kill me',
            'self harm',
            'self-harm',
            'hurt myself',
            'harm myself',
            'end my life',
            'do not want to live',
            "don't want to live",
            'dont want to live',
            'want to die',
            'better off dead',
            'overdose',
        ];
    }

    public function negativePhrases(): array
    {
        return [
            'sad',
            'stress',
            'stressed',
            'depressed',
            'anxiety',
            'anxious',
            'panic',
            'alone',
            'lonely',
            'lost',
            'hopeless',
            'overwhelmed',
            'burnout',
            'tired of everything',
        ];
    }
}
