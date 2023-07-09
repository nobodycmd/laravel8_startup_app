<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTgMessageJob;
use App\Models\Merchant;
use App\Services\QueueNameService;

class MessageController extends Controller
{
    public function sendMessage()
    {
        if (! request()->isMethod('get')) {
            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
                return get_response_message(1, '谷歌校验码错误', []);
            }
            $merchantids = request()->get('merchantid') ? request()->get('merchantid') : [];
            SendTgMessageJob::dispatch(request()->get('content'), $merchantids)->onQueue(QueueNameService::all_send_tg);

            return get_response_message(0, '发送成功', []);
        } else {
            $merchantResultRaw = Merchant::select('merchantid', 'name', 'payout_is_cycle', 'type', 'payin_is_cycle')->where('status', 1)->orderBy('id', 'asc')->get()->toArray();
            $merchantResult    = [];
            $typeList          = Merchant::type();
            if ($merchantResultRaw) {
                foreach ($merchantResultRaw as $k => $v) {
                    $merchantResult[$v['type']][$k] = $v;
                }
            }

            return view('admin.message.sendmessage', compact('merchantResult', 'typeList'));
        }
    }
}
