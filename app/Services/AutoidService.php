<?php

namespace App\Services;

use App\Models\AutoId;

class AutoidService
{
    public static function getID()
    {
        $m    = new AutoId();
        $m->t = time();
        $m->save();

        return $m->id;
    }
}
