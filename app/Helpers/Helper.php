<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function truncate($string, $length = 100, $ending = '...')
    {
        return Str::limit($string, $length, $ending);
    }
}
