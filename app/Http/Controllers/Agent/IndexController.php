<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function login()
    {
        if (request()->isMethod('get')) {
            if (auth('agent')->check()) {
                return redirect()->route('agent.index.system');
            } else {
                return view('agent.index.login');
            }
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'agentid'  => 'bail|required',
                'password' => 'bail|required',
//                'vercode' => 'bail|required|captcha',
                'gc' => 'bail|required',
            ], [
                'agentid.required'  => '代理号不能为空',
                'password.required' => '密码不能为空',
//                'vercode.required' => '验证码不能为空',
//                'vercode.captcha' => '验证码错误',
                'gc.required' => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! auth('agent')->attempt(['agentid' => $input['agentid'], 'password' => $input['password'].config('config.secret_key')], true)) {
                return get_response_message(1, '代理号或密码错误', []);
            }

            if (auth('agent')->user()->status != 1) {
                auth('agent')->logout();

                return get_response_message(1, '该代理已被禁用', []);
            }

            if (! get_google_authenticator_checkcode(auth('agent')->user()->google_authenticator, $input['gc'])) {
                auth('agent')->logout();

                return get_response_message(1, '谷歌校验码错误', []);
            }

            return get_response_message(0, '登陆成功', []);
        }
    }

    public function password()
    {
        if (request()->isMethod('get')) {
            return view('agent.index.password');
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

            if (! Hash::check($input['current_password'].config('config.secret_key'), auth('agent')->user()->password)) {
                return get_response_message(1, '当前密码错误', []);
            }

            if (! get_google_authenticator_checkcode(auth('agent')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $result = Agent::where('id', auth('agent')->user()->id)->update(['password' => bcrypt($input['password'].config('config.secret_key')), 'update_time' => time()]);
            if (! $result) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function logout()
    {
        auth('agent')->logout();

        return redirect()->route('agent.index.login');
    }
}
