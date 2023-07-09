<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Services\OSS;

class LogoController extends Controller
{
    public function index()
    {
        if (! request()->ajax()) {
            $Merchant_info = Merchant::where('merchantid', auth('merchant')->user()->merchantid)->first()->toArray();

            return view('merchant.logo.index', compact('Merchant_info'));
        } else {
            $input = request()->all();

            if (isset($input['type']) && $input['type'] == 'clear_info') {
                $validator = validator($input, [
                    'gc' => 'bail|required',
                ], [
                    'gc.required' => '谷歌校验码不能为空',
                ]);

                if ($validator->fails()) {
                    return get_response_message(1, $validator->errors()->first(), []);
                }

                if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, $input['gc'])) {
                    return get_response_message(1, '谷歌校验码错误', []);
                }

                $save = Merchant::where('merchantid', auth('merchant')->user()->merchantid)->update(['product_name' => '', 'product_logo' => '']);

                if ($save) {
                    return get_response_message(0, '清除成功', []);
                } else {
                    return get_response_message(1, '保存失败', []);
                }
            } else {
                $validator = validator($input, [
                    'product_name' => 'bail|required',
                    'product_logo' => 'bail|required',
                    'gc'           => 'bail|required',
                ], [
                    'product_name.required' => '产品名称不能为空',
                    'product_logo.required' => 'logo不能为空',
                    'gc.required'           => '谷歌校验码不能为空',
                ]);

                if ($validator->fails()) {
                    return get_response_message(1, $validator->errors()->first(), []);
                }

                if (verifiContainZh($input['product_name'])) {
                    return get_response_message(1, $input['product_name'].' :产品名称不能有中文', []);
                }

                if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, $input['gc'])) {
                    return get_response_message(1, '谷歌校验码错误', []);
                }

                $save = Merchant::where('merchantid', auth('merchant')->user()->merchantid)->update(['product_name' => $input['product_name'], 'product_logo' => $input['product_logo']]);

                if ($save) {
                    return get_response_message(0, '保存成功', []);
                } else {
                    return get_response_message(1, '保存失败', []);
                }
            }
        }
    }

    public function upload()
    {
        $return        = [];
        $return['url'] = '';

        $transaction = true;

        $file = request()->file('file');

        if (! $file->isValid()) {
            $transaction = false;
        } else {
            if (! in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])) {
                $transaction = false;
            }

            if (! in_array($file->getClientMimeType(), ['image/jpeg', 'image/png'])) {
                $transaction = false;
            }

            if (strpos(strtolower($file->getClientOriginalName()), 'php') !== false) {
                $transaction = false;
            }

            if (! $transaction) {
                return get_response_message(1, '格式不对', $return);
            }

            if (ceil(filesize($file->getRealPath()) / 1024) > 50) {
                return get_response_message(1, '上传图片大小超过了50k', $return);
            }

//            $filename = auth('merchant')->user()->merchantid.'/'.get_unique_number().'.'.$file->getClientOriginalExtension();
            $filename = auth('merchant')->user()->merchantid.'/';

            $oss_return    = OSS::uploadFile('static/upload/logo/'.$filename, $file->getRealPath());
            $return['url'] = $oss_return;
        }

        if ($transaction) {
            return get_response_message(0, '上传成功', $return);
        } else {
            return get_response_message(0, '上传失败', $return);
        }
    }
}
