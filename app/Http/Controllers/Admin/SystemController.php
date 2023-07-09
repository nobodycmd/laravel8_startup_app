<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayinChannel;
use App\Models\SystemConf;
use App\Services\RedisService;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    public function config()
    {
        if (request()->isMethod('get')) {
            $info                    = SystemConf::query()->first()->toArray();
            $info['settlement_time'] = date('Y-m-d', $info['settlement_time']);
            $info['packet_isopen']   = 0;
            if (RedisService::getValue('packet_isopen')) {
                $info['packet_isopen'] = 1;
            }
            $payinchannel_list = PayinChannel::query()->get()->toArray();

            return view('admin.system.config', compact('info', 'payinchannel_list'));
        } else {
            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $input                                            = request()->all();
            $id                                               = $input['id'];
            $systemConfigData                                 = [];
            $systemConfigData['payin_frequency']              = $input['payin_frequency'];
            $systemConfigData['payinquery_frequency']         = $input['payinquery_frequency'];
            $systemConfigData['payout_frequency']             = $input['payout_frequency'];
            $systemConfigData['payoutquery_frequency']        = $input['payoutquery_frequency'];
            $systemConfigData['payoutbalancequery_frequency'] = $input['payoutbalancequery_frequency'];
            $systemConfigData['is_settlement']                = $input['is_settlement'];
            $systemConfigData['D0_is_settlement']             = $input['D0_is_settlement'];
            $systemConfigData['settlement_time']              = strtotime($input['settlement_time']);
            $systemConfigData['unservedregion_isopen']        = $input['unservedregion_isopen'];
            $systemConfigData['unservedregion_payinchannel']  = $input['unservedregion_payinchannel'];
            $systemConfigData['packet_time']                  = $input['packet_time'] ?? 10;
            try {
                $save = SystemConf::where('id', $id)->update($systemConfigData);
                RedisService::setValue('payin_frequency', $input['payin_frequency']);
                RedisService::setValue('payinquery_frequency', $input['payinquery_frequency']);
                RedisService::setValue('payout_frequency', $input['payout_frequency']);
                RedisService::setValue('payoutquery_frequency', $input['payoutquery_frequency']);
                RedisService::setValue('unservedregion_isopen', $input['unservedregion_isopen']);
                RedisService::setValue('unservedregion_payinchannel', $input['unservedregion_payinchannel']);
                if ($input['packet_isopen']) {
                    if (! RedisService::getValue('packet_isopen')) {
                        if ($input['packet_time'] > 0) {
                            RedisService::lock('packet_isopen', 60 * $input['packet_time']);
                        } else {
                            RedisService::setValue('packet_isopen', 'true');
                        }
                    }
                } else {
                    RedisService::del('packet_isopen');
                }

                return get_response_message(0, '保存成功', []);
            } catch (\Exception $e) {
                return get_response_message(1, '保存失败', []);
            }
        }
    }

    public function clearcache()
    {
//        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return get_response_message(0, '已清除缓存', []);
    }
}
