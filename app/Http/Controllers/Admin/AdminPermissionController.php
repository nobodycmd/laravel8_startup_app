<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;

class AdminPermissionController extends Controller
{
    public function index()
    {
        $list = [];

        $result1 = AdminPermission::where('pid', 0)->orderBy('sort', 'asc')->get();

        foreach ($result1 as $k1 => $v1) {
            $list[]  = $v1;
            $result2 = AdminPermission::where('pid', $v1['id'])->orderBy('sort', 'asc')->get();

            foreach ($result2 as $k2 => $v2) {
                $v2['name'] = '&nbsp;&nbsp;&nbsp;├ '.$v2['name'];
                $list[]     = $v2;
                $result3    = AdminPermission::where('pid', $v2['id'])->orderBy('sort', 'asc')->get();

                foreach ($result3 as $k3 => $v3) {
                    $v3['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├ '.$v3['name'];
                    $list[]     = $v3;
                }
            }
        }

        return view('admin.adminpermission.index', compact('list'));
    }

    public function create()
    {
        if (request()->isMethod('get')) {
            $adminPermissionPidList = self::getAdminPermissionPidList();

            return view('admin.adminpermission.create', compact('adminPermissionPidList'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name' => 'bail|required',
                'sort' => 'bail|required',
                'gc'   => 'bail|required',
            ], [
                'name.required' => '名称不能为空',
                'sort.required' => '排序值不能为空',
                'gc.required'   => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if ($input['pid'] != 0 && empty($input['uri'])) {
                return get_response_message(1, 'URI不能为空', []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $adminPermissionData                = [];
            $adminPermissionData['pid']         = $input['pid'];
            $adminPermissionData['name']        = $input['name'];
            $adminPermissionData['uri']         = $input['pid'] == 0 ? '' : $input['uri'];
            $adminPermissionData['sort']        = $input['sort'];
            $adminPermissionData['status']      = $input['status'];
            $adminPermissionData['create_time'] = time();
            $adminPermissionData['update_time'] = time();
            $adminPermissionResult              = AdminPermission::insertGetId($adminPermissionData);
            if (! $adminPermissionResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function edit()
    {
        if (request()->isMethod('get')) {
            $adminPermissionPidList = self::getAdminPermissionPidList();
            $list                   = AdminPermission::where('id', request()->input('id'))->first()->toArray();

            return view('admin.adminpermission.edit', compact('adminPermissionPidList', 'list'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name' => 'bail|required',
                'sort' => 'bail|required',
                'gc'   => 'bail|required',
            ], [
                'name.required' => '名称不能为空',
                'sort.required' => '排序值不能为空',
                'gc.required'   => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if ($input['pid'] != 0 && empty($input['uri'])) {
                return get_response_message(1, 'URI不能为空', []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $adminPermissionData                = [];
            $adminPermissionData['pid']         = $input['pid'];
            $adminPermissionData['name']        = $input['name'];
            $adminPermissionData['uri']         = $input['pid'] == 0 ? '' : $input['uri'];
            $adminPermissionData['sort']        = $input['sort'];
            $adminPermissionData['status']      = $input['status'];
            $adminPermissionData['update_time'] = time();
            $adminPermissionResult              = AdminPermission::where('id', $input['id'])->update($adminPermissionData);
            if (! $adminPermissionResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    private function getAdminPermissionPidList()
    {
        $adminPermissionPidList = [];

        $result1 = AdminPermission::select('id', 'name')->where('pid', 0)->orderBy('sort', 'asc')->get()->toArray();
        if ($result1) {
            foreach ($result1 as $k1 => $v1) {
                $adminPermissionPidList[] = $v1;
                $result2                  = AdminPermission::select('id', 'name')->where('pid', $v1['id'])->orderBy('sort', 'asc')->get()->toArray();
                if ($result2) {
                    foreach ($result2 as $k2 => $v2) {
                        $v2['name']               = '├ '.$v2['name'];
                        $adminPermissionPidList[] = $v2;
                    }
                }
            }
        }

        return $adminPermissionPidList;
    }
}
