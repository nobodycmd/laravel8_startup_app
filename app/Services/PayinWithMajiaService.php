<?php

namespace App\Services;

use App\Models\Merchant;
use App\Models\PayinChannel;
use App\Models\PayinOrder;
use App\Models\PayoutChannel;
use Illuminate\Support\Str;

class PayinWithMajiaService
{
    public function query(PayinOrder $payinorder)
    {
        $payinchannelResult = PayinChannel::find($payinorder->payin_channel_id);
        $merchant           = Merchant::where('merchantid', $payinorder->merchantid)->first();

        $data                    = [];
        $data['request_domain']  = env('APP_URL', '');
        $data['key']             = $payinchannelResult->key;
        $data['salt']            = $payinchannelResult->salt;
        $data['security_domain'] = $payinchannelResult->security_domain;

        $data['tn']                    = $payinorder->tn;
        $data['order_number']          = $payinorder->order_number;
        $data['id']                    = $payinorder->id;
        $data['upstream_order_number'] = $payinorder->upstream_order_number;
        $data['off_fee']               = $data['total_fee'] = $payinorder->off_fee;

        $data['customer_mobile'] = $payinorder->customer_mobile;
        $data['customer_email']  = $payinorder->customer_email;
        $data['customer_name']   = $payinorder->customer_name;

        $data['product_name'] = $merchant->product_name ?? '';
        $data['product_logo'] = $merchant->product_logo ?? '';

        if (Str::endsWith($payinchannelResult->security_domain, '/')) {
            $url = $payinchannelResult->security_domain.$payinchannelResult->identity.'/paymentquery.php';
        } else {
            $url = $payinchannelResult->security_domain.'/'.$payinchannelResult->identity.'/paymentquery.php';
        }
        $res = get_curl_post($url, [
            'data' => Majia::toMajiaMachineData($data),
        ]);

        /*
         * $res = (json_encode([
        'code' => 1,
        'res' => $res,
        'money' => '', //真实支付金额
        ]));  表示已成功支付
         */
        $res = json_decode($res, true);
        if ($res['code'] == 1 && $payinorder->status == PayinOrder::STATUS_ING) {
            $money = isset($res['money']) ? $res['money'] : false;
            PaySuccessService::payinOrderSuccess($payinorder->order_number, '', '', '', '', $money);
        }

        return $res;
    }

    public function getBalance($channelId)
    {
        $channelInfo = PayinChannel::where('id', $channelId)->first();

        $key             = $channelInfo['key'];
        $salt            = $channelInfo['salt'];
        $security_domain = $channelInfo['security_domain'];
        $identity        = $channelInfo['identity'];

        $data         = [];
        $data['key']  = $key;
        $data['salt'] = $salt;
        try {
            if (Str::endsWith($security_domain, '/')) {
                $url = $security_domain.$identity.'/balance.php';
            } else {
                $url = $security_domain.'/'.$identity.'/balance.php';
            }

            $response = json_decode(get_curl_post($url, $data), true);

            if ($response['code'] == 1) {
                $channelInfo->balance = $response['balance'];
                $channelInfo->save();

                $same = PayoutChannel::where('identity', $channelInfo->identity)->where('key', $channelInfo->key)->first();
                if ($same) {
                    $same->balance = $response['balance'];
                    $same->save();
                }

                return $channelInfo->balance;
            }
        } catch (\Exception $e) {
        }

        return false;
    }
}
