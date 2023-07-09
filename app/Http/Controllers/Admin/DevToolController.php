<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemInfo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevToolController extends Controller
{
    public function index()
    {
        return view('admin.devtool.index');
    }

    public function tables()
    {
        $aryList = DB::select('SHOW TABLE STATUS');

        return view('admin.devtool.tables', compact('aryList'));
    }

    public function rows()
    {
        $table = request()->input('table');
        $where = request()->input('where', '');
        $limit = request()->input('limit', 30);
        $page  = request()->input('page', 1);

        $where = strtolower($where);
        if (Str::contains($where, 'delete') || Str::contains($where, 'update')) {
            $where = '';
        }

        $aryColumn = DB::select("SHOW FULL columns from $table");

        $sql = "select * from $table ";
        if ($where) {
            $sql .= " where  $where ";
        }

        $sqlC = "select count(*) as c from $table ";
        if ($where) {
            $sqlC .= " where  $where ";
        }
        $count = DB::select($sqlC)[0]->c;

        $paginator = new LengthAwarePaginator([], $count, $limit, $page, ['path' => request()->url(), 'query' => request()->query()]);

        $sql .= ' order by id desc limit '.($page - 1) * $limit.",$limit";

        $aryList = DB::select($sql);

        return view('admin.devtool.rows', compact('aryColumn', 'aryList', 'paginator'));
    }

    public function codes()
    {
        $table     = request()->input('table');
        $aryColumn = DB::select("SHOW FULL columns from $table");
        $className = ucfirst(Str::camel($table));

        return view('admin.devtool.codes', compact('table', 'className', 'aryColumn'));
    }

    public function phpinfo()
    {
        return view('admin.devtool.phpinfo');
    }

    public function probe()
    {
        $info = $this->getProbeInfo();

        return view('admin.devtool.probe', compact('info'));
    }

    private function getProbeInfo()
    {
        $info       = new SystemInfo();
        $systemInfo = [
            'environment' => $info->getEnvironment(),
            'hardDisk'    => $info->getHardDisk(),
            'cpu'         => $info->getCpu(),
            'cpuUse'      => $info->getCpuUse(),
            'netWork'     => $info->getNetwork(),
            'memory'      => $info->getMemory(),
            'loadavg'     => $info->getLoadavg(),
            'uptime'      => $info->getUptime(),
            'time'        => time(),
        ];

        empty($systemInfo['cpuUse']['explain']) && $systemInfo['cpuUse']['explain'] = '正在计算...';

        // 网络数字转格式
        $systemInfo['netWork']['allOutSpeed']   = round($systemInfo['netWork']['allOutSpeed'] / (1024 * 1024 * 1024), 2);
        $systemInfo['netWork']['allInputSpeed'] = round($systemInfo['netWork']['allInputSpeed'] / (1024 * 1024 * 1024), 2);

        $num     = 3;
        $num_arr = [];
        for ($i = 20; $i >= 1; $i--) {
            $num_arr[] = date('H:i:s', time() - $i * $num);
        }

        $systemInfo['chartTime'] = $num_arr;

        return $systemInfo;
    }
}
