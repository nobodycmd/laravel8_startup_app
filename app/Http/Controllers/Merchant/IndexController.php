<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function login()
    {
        if (request()->isMethod('get')) {
            if (auth('merchant')->check()) {
                return redirect()->route('merchant.homepage.console');
            } else {
                return view('merchant.index.login');
            }
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'merchantid' => 'bail|required',
                'password'   => 'bail|required',
//                'vercode' => 'bail|required|captcha',
                'gc' => 'bail|required',
            ], [
                'merchantid.required' => '商户号不能为空',
                'password.required'   => '密码不能为空',
                'gc.required'         => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! auth('merchant')->attempt(['merchantid' => $input['merchantid'], 'password' => $input['password'].config('config.secret_key')], true)) {
                return get_response_message(1, '商户号或密码错误', []);
            }

            if (auth('merchant')->user()->status != 1) {
                auth('merchant')->logout();

                return get_response_message(1, '该商户已被禁用', []);
            }

            if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, $input['gc'])) {
                auth('merchant')->logout();

                return get_response_message(1, '谷歌校验码错误', []);
            }

            return get_response_message(0, '登陆成功', []);
        }
    }

    public function password()
    {
        if (request()->isMethod('get')) {
            return view('merchant.index.password');
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

            if (! Hash::check($input['current_password'].config('config.secret_key'), auth('merchant')->user()->password)) {
                return get_response_message(1, '当前密码错误', []);
            }

            if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $result = Merchant::where('id', auth('merchant')->user()->id)->update(['password' => bcrypt($input['password'].config('config.secret_key')), 'update_time' => time()]);
            if (! $result) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function logout()
    {
        auth('merchant')->logout();

        return redirect()->route('merchant.index.login');
    }
}
