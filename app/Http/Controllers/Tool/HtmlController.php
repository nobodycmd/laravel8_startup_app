<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class HtmlController extends Controller
{
    public function downloadTable()
    {
        $file       = 'output'.date('YmdHis').'.xls';
        $table_html = request()->input('html');
        if (Str::startsWith($table_html, '<table') == false) {
            $table_html = '<table>'.$table_html.'</table>';
        }
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=$file");
        $html = '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name></x:Name><x:WorksheetOptions><x:Selected/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= "<body>$table_html</body></html>";
        echo $html;
    }
}
