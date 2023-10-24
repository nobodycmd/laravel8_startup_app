<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemInfo;
use App\Services\DBBackAndRestoreService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevToolController extends Controller
{
    private $dbBackupAndStoreService;

    public function __construct(DBBackAndRestoreService $DBBackAndRestoreService)
    {
        $this->dbBackupAndStoreService = $DBBackAndRestoreService;
    }

    public function index()
    {
        return view('admin.devtool.index');
    }

    public function tables()
    {
        if (request()->isMethod('get')) {
            $aryList = DB::select('SHOW TABLE STATUS');

            return view('admin.devtool.tables', compact('aryList'));
        }
        $aryNames = request()->input('names', '');
        if ($aryNames == false) {
            return apiRenderError('no checked');
        }
        $this->dbBackupAndStoreService->setCtrlRes($aryNames);
        $this->dbBackupAndStoreService->runBak();

        return 'have backed up for tables of '.implode(' , ', $aryNames);
    }

    public function restore()
    {
        if (request()->isMethod('get')) {
            $aryList = $this->dbBackupAndStoreService->getList();

            return view('admin.devtool.restore', compact('aryList'));
        }
        $aryNames = request()->input('names', '');
        if ($aryNames == false) {
            return apiRenderError('no checked');
        }
        $this->dbBackupAndStoreService->setCtrlRes($aryNames);
        $this->dbBackupAndStoreService->runRes();

        return 'have restored for '.implode(',', $aryNames);
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

        if (request()->isMethod('get')) {
            return view('admin.devtool.columns', compact('table', 'className', 'aryColumn'));
        }
        $inputAll = request()->input();

        return view('admin.devtool.codes', compact('table', 'className', 'aryColumn', 'inputAll'));
    }
}
