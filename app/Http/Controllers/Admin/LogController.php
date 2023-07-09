<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Pagination\LengthAwarePaginator;

class LogController extends Controller
{
    public function merchantaccountlog()
    {
        $query = Journal::query();

//        $merchantId = auth('merchant')->user()->merchantid;
//
//        $query = $query->where('merchantid', '=', $merchantId);
//
        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $count = $query->count();
        $list  = $query->offset(($page - 1) * $limit)->limit($limit)->orderBy('id', 'desc')->get()->toArray();

        if ($list) {
            foreach ($list as &$v) {
                $v['type_name']   = Journal::getTypeName($v['type']);
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            }
        }

        $paginator = new LengthAwarePaginator($list, $count, $limit, $page, ['path' => request()->url(), 'query' => request()->query()]);

        return view('admin.log.merchantaccountlog', [
            'count'     => $count,
            'list'      => $list,
            'paginator' => $paginator,
        ]);
    }
}
