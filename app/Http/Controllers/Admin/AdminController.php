<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;

class AdminController extends Controller
{
    public function index()
    {
        $adminAdminRoleIdList = self::getAdminAdminRoleIdList();

        $query = Admin::query();

        if (request()->filled('admin_role_id')) {
            $query = $query->where('admin_role_id', '=', request()->input('admin_role_id'));
        }

        if (request()->filled('name')) {
            $query = $query->where('name', 'like', '%'.request()->input('name').'%');
        }

        $list = $query->get();

        return view('admin.admin.index', compact('adminAdminRoleIdList', 'list'));
    }

    public function create()
    {
        if (request()->isMethod('get')) {
            $adminAdminRoleIdList = self::getAdminAdminRoleIdList();

            return view('admin.admin.create', compact('adminAdminRoleIdList'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'admin_role_id' => 'bail|required',
                'name'          => 'bail|required|unique:admins',
                'mobile'        => ['bail', 'required', 'regex:/^1[3-9][0-9]\d{8}$/', 'unique:admins'],
                'username'      => ['bail', 'required', 'regex:/^[A-Za-z0-9\-\_]+$/', 'unique:admins'],
                'password'      => 'bail|required|min:6',
                'gc'            => 'bail|required',
            ], [
                'admin_role_id.required' => '请选择角色名称',
                'name.required'          => '姓名不能为空',
                'name.unique'            => '姓名已存在',
                'mobile.required'        => '手机号不能为空',
                'mobile.regex'           => '手机号格式错误',
                'mobile.unique'          => '手机号已存在',
                'username.required'      => '用户名不能为空',
                'username.regex'         => '用户名必须为字母、数字、破折号及下划线',
                'username.unique'        => '用户名已存在',
                'password.required'      => '密码不能为空',
                'password.min'           => '密码长度最小为6个字符',
                'gc.required'            => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $adminData                         = [];
            $adminData['admin_role_id']        = $input['admin_role_id'];
            $adminData['name']                 = $input['name'];
            $adminData['mobile']               = $input['mobile'];
            $adminData['username']             = $input['username'];
            $adminData['password']             = bcrypt($input['password'].config('config.secret_key'));
            $adminData['status']               = $input['status'];
            $adminData['remember_token']       = '';
            $adminData['google_authenticator'] = get_google_authenticator();
            $adminData['create_time']          = time();
            $adminData['update_time']          = time();
            $adminResult                       = Admin::insertGetId($adminData);
            if (! $adminResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function edit()
    {
        if (request()->isMethod('get')) {
            $adminAdminRoleIdList = self::getAdminAdminRoleIdList();
            $list                 = Admin::where('id', request()->input('id'))->first()->toArray();

            return view('admin.admin.edit', compact('adminAdminRoleIdList', 'list'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'admin_role_id' => 'bail|required',
                'name'          => 'bail|required|unique:admins,name,'.$input['id'],
                'mobile'        => ['bail', 'required', 'regex:/^1[3-9][0-9]\d{8}$/', 'unique:admins,mobile,'.$input['id']],
                'username'      => ['bail', 'required', 'regex:/^[A-Za-z0-9\-\_]+$/', 'unique:admins,username,'.$input['id']],
                'password'      => 'bail|nullable|min:6',
                'gc'            => 'bail|required',
            ], [
                'admin_role_id.required' => '请选择角色名称',
                'name.required'          => '姓名不能为空',
                'name.unique'            => '姓名已存在',
                'mobile.required'        => '手机号不能为空',
                'mobile.regex'           => '手机号格式错误',
                'mobile.unique'          => '手机号已存在',
                'username.required'      => '用户名不能为空',
                'username.regex'         => '用户名必须为字母、数字、破折号及下划线',
                'username.unique'        => '用户名已存在',
                'password.min'           => '密码长度最小为6个字符',
                'gc.required'            => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $adminData                  = [];
            $adminData['admin_role_id'] = $input['admin_role_id'];
            $adminData['name']          = $input['name'];
            $adminData['mobile']        = $input['mobile'];
            $adminData['username']      = $input['username'];
            if ($input['password']) {
                $adminData['password'] = bcrypt($input['password'].config('config.secret_key'));
            }
            $adminData['status']      = $input['status'];
            $adminData['update_time'] = time();
            $adminResult              = Admin::where('id', $input['id'])->update($adminData);
            if (! $adminResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    private function getAdminAdminRoleIdList()
    {
        $adminAdminRoleIdList = AdminRole::select('id', 'name')->orderBy('id', 'asc')->get()->toArray();

        return $adminAdminRoleIdList;
    }

    public function googleauthenticator()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $googleAuthenticator = Admin::where('id', request()->input('id'))->value('google_authenticator');

        return get_response_message(0, '查看成功', ['google_authenticator' => $googleAuthenticator]);
    }
}
