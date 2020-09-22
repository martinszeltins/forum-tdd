<?php

namespace App;
use Exception;
use Illuminate\Support\Str;

class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'Yahoo Customer Support',
        ];

        foreach ($invalidKeywords as $keyword)
        {
            if (Str::contains($body, $keyword)) {
                throw new Exception('Your reply contains spam');
            }
        }
    }
}