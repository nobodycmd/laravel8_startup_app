<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class OSS
{
    public static function uploadFile($ossPath, $filePath, $options = 'public')
    {
        $doPath = Storage::disk('digitalocean')->putFile($ossPath, $filePath, $options);

        return 'https://dos3.sgp1.cdn.digitaloceanspaces.com/'.$doPath;
    }
}
