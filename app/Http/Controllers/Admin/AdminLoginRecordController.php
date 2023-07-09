<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginRecord;

class AdminLoginRecordController extends Controller
{
    public function index()
    {
        $limit = request()->input('limit', 50);
        $page  = request()->input('page', 1);
        $query = AdminLoginRecord::query();
        if ($v = request()->input('id', '')) {
            $query->where('id', '=', $v);
        }
        if ($v = request()->input('admin_id', '')) {
            $query->where('admin_id', '=', $v);
        }
        if ($v = request()->input('login_ip', '')) {
            $query->where('login_ip', '=', $v);
        }
        if ($v = request()->input('login_time', '')) {
            $query->where('login_time', '=', $v);
        }
        if ($v = request()->input('create_time', '')) {
            $query->where('create_time', '=', $v);
        }
        if ($v = request()->input('update_time', '')) {
            $query->where('update_time', '=', $v);
        }
        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();

        return view('admin.adminloginrecord.index', compact('paginator', 'list'));
    }
}
