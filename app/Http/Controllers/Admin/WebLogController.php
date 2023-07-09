<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebLog;
use App\Services\WebLogServer;

class WebLogController extends Controller
{
    public function index()
    {
        $limit = request()->input('limit', 50);
        $page  = request()->input('page', 1);
        $query = WebLog::query();
        if ($v = request()->input('id', '')) {
            $query->where('id', '=', $v);
        }
        if ($v = request()->input('model', '')) {
            $query->where('model', '=', $v);
        }
        if ($v = request()->input('controll', '')) {
            $query->where('controll', '=', $v);
        }
        if ($v = request()->input('action', '')) {
            $query->where('action', '=', $v);
        }
        if ($v = request()->input('request_type', '')) {
            $query->where('request_type', '=', $v);
        }
        if ($v = request()->input('params', '')) {
            $query->where('params', '=', $v);
        }
        if ($v = request()->input('admin_user_id', '')) {
            $query->where('admin_user_id', '=', $v);
        }
        if ($v = request()->input('create_time', '')) {
            $query->where('create_time', '=', $v);
        }
        if ($v = request()->input('request_ip', '')) {
            $query->where('request_ip', '=', $v);
        }
        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();

        return view('admin.weblog.index', compact('paginator', 'list'));
    }

    /**
     * 获取详情.
     */
    public function detail()
    {
        $id   = request()->get('id');
        $list = WebLogServer::getById($id);

        return view('admin.weblog.detail', compact('list'));
    }
}
