<?php

namespace App\Inspections;

use Exception;
use Illuminate\Support\Str;

class InvalidKeywords
{
    protected $keywords = [
        'Yahoo Customer Support',
    ];

    public function detect($body)
    {
        foreach ($this->keywords as $keyword)
        {
            if (Str::contains($body, $keyword)) {
                throw new Exception('Your reply contains spam');
            }
        }
    }
}
