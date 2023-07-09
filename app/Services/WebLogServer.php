<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\WebLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WebLogServer
{
    /**
     * 添加日志.
     */
    public static function setLog(Request $request)
    {
        $action   = $request->route()->getAction();
        $info     = $action['as'] ?? '';
        $model    = '';
        $controll = '';
        $action   = '';
        if ($info) {
            list($model, $controll, $action) = explode('.', $info);
        }
        $data = [
            'controll'      => $controll,
            'model'         => $model,
            'action'        => $action,
            'request_type'  => request()->method(),
            'params'        => json_encode(request()->all()),
            'admin_user_id' => auth('admin')->user()->id ?? 0,
        ];

        WebLog::query()->insertGetId($data);
    }

    /**
     * 获取数据.
     * @param $params
     */
    public static function getData(array $params)
    {
        $model = WebLog::query();
        if (isset($params['model'])) {
            $model->where('model', $params['model']);
        }
        if (isset($params['controll'])) {
            $model->where('controll', $params['controll']);
        }
        if (isset($params['action'])) {
            $model->where('action', $params['action']);
        }
        if (isset($params['admin_id'])) {
            $model->where('admin_user_id', $params['admin_id']);
        }
        if (isset($params['params'])) {
            $model->where('params', 'like', '%'.$params['params'].'%');
        }
        if (isset($params['create_time'])) {
            list($startDate, $endDate) = explode('|', $params['create_time']);
            $endDate                   = date('Y-m-d', strtotime(trim($endDate)) + 3600 * 24);
            $model->whereBetween('create_time', [trim($startDate), trim($endDate)]);
        }
        $page  = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $count = $model->count();
        $list  = $model->offset(($page - 1) * $limit)->limit($limit)->orderBy('id', 'desc')->get()->toArray();
        foreach ($list as $key => $val) {
            $list[$key]['admin_user'] =  Admin::where('id', $val['admin_user_id'])->value('name');
        }

        return ['count' => $count, 'list' => $list];
    }

    /**
     * 根据ID获取详情.
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function getById($id)
    {
        $data = WebLog::query()->find($id);
        if ($data) {
            $data               = $data->toArray();
            $data['admin_user'] = Admin::where('id', $data['admin_user_id'])->value('name');
        }

        return $data;
    }

    public static function getRoutes()
    {
        dd(Route::getRoutes()->getRoutesByName());
    }
}
