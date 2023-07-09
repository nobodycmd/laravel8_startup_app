<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use App\Models\AdminRole;

class AdminRoleController extends Controller
{
    public function index()
    {
        $list = AdminRole::get();
        return view('admin.adminrole.index',compact('list'));
    }

    public function create()
    {
        if (request()->isMethod('get')) {
            return view('admin.adminrole.create');
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name'     => 'bail|required|unique:admin_roles',
                'identity' => ['bail', 'required', 'regex:/^[A-Za-z0-9\-\_]+$/', 'unique:admin_roles'],
                'gc'       => 'bail|required',
            ], [
                'name.required'     => '名称不能为空',
                'name.unique'       => '名称已存在',
                'identity.required' => '标识不能为空',
                'identity.regex'    => '标识必须为字母、数字、破折号及下划线',
                'identity.unique'   => '标识已存在',
                'gc.required'       => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $adminRoleData                = [];
            $adminRoleData['name']        = $input['name'];
            $adminRoleData['identity']    = $input['identity'];
            $adminRoleData['status']      = $input['status'];
            $adminRoleData['permission']  = '';
            $adminRoleData['create_time'] = time();
            $adminRoleData['update_time'] = time();
            $adminRoleResult              = AdminRole::insertGetId($adminRoleData);
            if (! $adminRoleResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function edit()
    {
        if (request()->isMethod('get')) {
            $list = AdminRole::where('id', request()->input('id'))->first()->toArray();

            return view('admin.adminrole.edit', compact('list'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name'     => 'bail|required|unique:admin_roles,name,'.$input['id'],
                'identity' => ['bail', 'required', 'regex:/^[A-Za-z0-9\-\_]+$/', 'unique:admin_roles,identity,'.$input['id']],
                'gc'       => 'bail|required',
            ], [
                'name.required'     => '名称不能为空',
                'name.unique'       => '名称已存在',
                'identity.required' => '标识不能为空',
                'identity.regex'    => '标识必须为字母、数字、破折号及下划线',
                'identity.unique'   => '标识已存在',
                'gc.required'       => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $adminRoleData                = [];
            $adminRoleData['name']        = $input['name'];
            $adminRoleData['identity']    = $input['identity'];
            $adminRoleData['status']      = $input['status'];
            $adminRoleData['update_time'] = time();
            $adminRoleResult              = AdminRole::where('id', $input['id'])->update($adminRoleData);
            if (! $adminRoleResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function permission()
    {

            if (request()->isMethod('get')) {
                $roleid = request()->input('id');
                $permission = AdminRole::where('id', $roleid)->value('permission');
                if (empty($permission)) {
                    $permission = [];
                } else {
                    $permission = explode('|', $permission);
                }

                $list = [];

                $result1 = AdminPermission::where('pid', 0)->orderBy('sort', 'asc')->get();
                if ($result1) {
                    foreach ($result1 as $k1 => $v1) {
                        $v1->LAY_CHECKED = in_array($v1['id'], $permission);
                        $list[]  = $v1;
                        $result2 = AdminPermission::where('pid', $v1['id'])->orderBy('sort', 'asc')->get();
                        if ($result2) {
                            foreach ($result2 as $k2 => $v2) {
                                $v2->LAY_CHECKED = in_array($v2['id'], $permission);
                                $v2['name'] = '&nbsp;&nbsp;&nbsp;├ '.$v2['name'];
                                $list[]     = $v2;
                                $result3    = AdminPermission::where('pid', $v2['id'])->orderBy('sort', 'asc')->get();
                                if ($result3) {
                                    foreach ($result3 as $k3 => $v3) {
                                        $v3->LAY_CHECKED = in_array($v3['id'], $permission);
                                        $v3['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├ '.$v3['name'];
                                        $list[]     = $v3;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($list) {
                    foreach ($list as $k => $v) {
                        $list[$k]['status'] = AdminPermission::status($v['status']);
                    }
                }

                return view('admin.adminrole.permission',compact('list','roleid'));
            } else {
                $permission = request()->input('ids');
                if (empty($permission)) {
                    $permission = '';
                }else{
                    $permission = implode('|',$permission);
                }

                $adminRoleResult = AdminRole::where('id', request()->input('roleid'))->update(['permission' => $permission, 'update_time' => time()]);
                if (! $adminRoleResult) {
                    return get_response_message(1, 'fail', [
                        'ids' => request()->input('ids'),
                        'roleid' => request()->input('roleid'),
                        ]);
                }

                return get_response_message(0, '', [
                    'ids' => request()->input('ids'),
                    'roleid' => request()->input('roleid'),
                ]);
            }

    }
}
