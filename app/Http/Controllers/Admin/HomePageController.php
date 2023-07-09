<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchatOrderStatistics;
use App\Models\PayinOrder;
use App\Models\PayoutOrder;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function console()
    {
        return view('admin.homepage.console');
    }

}
