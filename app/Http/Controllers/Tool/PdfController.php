<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function download()
    {
        require app_path('Utils/dompdf/index.php');
        \dompdf::topdf($_POST['html']);
    }
}
