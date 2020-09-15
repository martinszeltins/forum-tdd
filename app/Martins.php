<?php

namespace App;

class Martins
{
    public $name = 'Martins Zeltins';

    public function __construct($name = '')
    {
        if ($name) $this->name = $name;
    }
}