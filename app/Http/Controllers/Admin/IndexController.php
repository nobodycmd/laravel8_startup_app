<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLoginRecord;
use App\Models\AdminRole;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function login()
    {
        if (request()->isMethod('get')) {
            if (auth('admin')->check()) {
                return redirect()->route('admin.homepage.console');
            } else {
                return view('admin.index.login');
            }
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'username' => 'bail|required',
                'password' => 'bail|required',
//                'gc'       => 'bail|required',
            ], [
                'username.required' => '用户名不能为空',
                'password.required' => '密码不能为空',
//                'gc.required'       => '验证码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! auth('admin')->attempt([
                'username' => $input['username'],
                'password' => $input['password'].config('config.secret_key'),
            ],false)) {
                return get_response_message(1, '用户名或密码错误', []);
            }

            $adminRoleStatus = AdminRole::where('id', auth('admin')->user()->admin_role_id)->value('status');
            if ($adminRoleStatus != 1 || auth('admin')->user()->status != 1) {
                auth('admin')->logout();

                return get_response_message(1, '该用户已被禁用', []);
            }

            $log = new AdminLoginRecord();
            $log->admin_id = auth('admin')->user()->getAuthIdentifier();
            $log->login_ip = request()->ip();
            $log->login_time = $log->create_time = $log->update_time = time();

            if ( $log->save() == false ) {
                auth('admin')->logout();

                return get_response_message(1, config('config.error'), []);
            }

//            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
//                auth('admin')->logout();
//                return get_response_message(1, '验证码错误', []);
//            }

            return get_response_message(0, '登陆成功', []);
        }
    }

    public function system()
    {
        return view('admin.index.system');
    }

    public function password()
    {
        if (request()->isMethod('get')) {
            return view('admin.index.password');
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'current_password' => 'bail|required',
                'password'         => 'bail|required|min:6|confirmed',
                'gc'               => 'bail|required',
            ], [
                'current_password.required' => '当前密码不能为空',
                'password.required'         => '新密码不能为空',
                'password.min'              => '新密码长度最小为6个字符',
                'password.confirmed'        => '新密码与确认新密码不一致',
                'gc.required'               => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! Hash::check($input['current_password'].config('config.secret_key'), auth('admin')->user()->password)) {
                return get_response_message(1, '当前密码错误', []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $result = Admin::where('id', auth('admin')->user()->id)->update(['password' => bcrypt($input['password'].config('config.secret_key')), 'update_time' => time()]);
            if (! $result) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function logout()
    {
        auth('admin')->logout();

        return redirect()->route('admin.index.login');
    }
}
