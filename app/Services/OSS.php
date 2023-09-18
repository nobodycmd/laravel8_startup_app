<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class OSS
{
    /**
     * @param $ossDirPath
     * @param $localFilePath
     * @param $name
     * @param string $options
     * @return string
     */
    public static function uploadFile($ossDirPath, $localFilePath, $name, $options = 'public')
    {
        $doPath = Storage::disk('digitalocean')->putFileAs($ossDirPath, $localFilePath, $name, $options);

        return env('SPACES_URL','https://dos3.sgp1.cdn.digitaloceanspaces.com') . '/' . $doPath;
//        return 'https://dos3.sgp1.cdn.digitaloceanspaces.com/'.$doPath;
    }
}
