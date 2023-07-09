<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    public function console()
    {
        return view('agent.homepage.console');
    }
}
