<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\Merchant;
use App\Models\MerchatOrderStatistics;
use App\Services\AccountService;
use Illuminate\Pagination\LengthAwarePaginator;

class MerchantController extends Controller
{
    public function info()
    {
        $merchantId                       = auth('merchant')->user()->merchantid;
        $merchantList                     = Merchant::where('merchantid', $merchantId)->first();

        $country = env('COUNTRY');

        return view('merchant.merchant.info', compact('merchantList', 'country'));
    }

    public function code()
    {
        header('location: /demo/index.php');

        return '';
    }

    public function resetkey()
    {
        if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $secretkey = Merchant::where('id', auth('merchant')->user()->id)->value('secretkey');

        return get_response_message(0, '谷歌密钥'.$secretkey, ['secretkey' => $secretkey]);
    }

    public function seekey()
    {
        if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $merchantData                = [];
        $merchantData['secretkey']   = get_unique_identity();
        $merchantData['update_time'] = time();
        $merchantResult              = Merchant::where('id', auth('merchant')->user()->id)->update($merchantData);
        if (! $merchantResult) {
            return get_response_message(1, config('config.error'), []);
        }

        return get_response_message(0, '重置密钥成功', []);
    }

    public function seegc()
    {
        if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $googleAuthenticator = Merchant::where('id', auth('merchant')->user()->id)->value('google_authenticator');

        return get_response_message(0, '查看成功', ['google_authenticator' => $googleAuthenticator]);
    }

    public function resetgc()
    {
        if (! get_google_authenticator_checkcode(auth('merchant')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $merchantData                         = [];
        $merchantData['google_authenticator'] = get_google_authenticator();
        $merchantData['update_time']          = time();
        $merchantResult                       = Merchant::where('id', auth('merchant')->user()->id)->update($merchantData);
        if (! $merchantResult) {
            return get_response_message(1, config('config.error'), []);
        }

        return get_response_message(0, '重置成功', ['google_authenticator' => $merchantData['google_authenticator']]);
    }

    public function accountlog()
    {
        $query = Journal::query();

        $merchantId = auth('merchant')->user()->merchantid;

        $query = $query->where('merchantid', '=', $merchantId);

        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $count = $query->count();
        $list  = $query->offset(($page - 1) * $limit)->limit($limit)->orderBy('create_time', 'desc')->get()->toArray();
        if ($list) {
            foreach ($list as &$v) {
                $v['type_name']   = Journal::getTypeName($v['type']);
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            }
        }

        $paginator = new LengthAwarePaginator($list, $count, $limit, $page, ['path' => request()->url(), 'query' => request()->query()]);

        return view('merchant.merchant.accountlog', [
            'balance'     => AccountService::getBalanceIncludeWaiting($merchantId),
            'wait_settle' => AccountService::getWaitSettle($merchantId),
            'freeze'      => AccountService::freezonMoney($merchantId),
            'count'       => $count,
            'list'        => $list,
            'paginator'   => $paginator,
        ]);
    }

    public function merchantorderstatistic()
    {
        $request = request();

        if ($request->filled('days')) {
            list($startDate, $endDate) = explode(' | ', request()->input('days'));
        } else {
            $startDate = date('Y-m-d');
            $endDate   = date('Y-m-d');
        }

        $authMerchant = auth('merchant');
        $merchantId   = $authMerchant->user()->merchantid;

        $query = MerchatOrderStatistics::query()->whereBetween('days', [$startDate, $endDate]);

        $query->where('merchantid', $merchantId);

        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('days')->get();

        return view('merchant.payinorder.merchantorderstatistic', compact('list', 'paginator'));
    }

    /**
     * 商户间转账.
     */
    public function innertransfer()
    {
        $request                          = request();
        $merchantId                       = auth('merchant')->user()->merchantid;
        $merchant                         = Merchant::where('merchantid', $merchantId)->first();

        if ($request->isMethod('get')) {
            $myOthersMerchants = Merchant::query()->where('identity', $merchant->identity)->where('id', '<>', $merchant->id)->get();

            return view('merchant.merchant.innertransfer', compact('myOthersMerchants', 'merchant'));
        }

        if (! get_google_authenticator_checkcode($merchant->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $targetMerchant = Merchant::query()->where('identity', $merchant->identity)
            ->where('merchantid', $request->input('merchantid'))->first();

        if ($targetMerchant == false) {
            return get_response_message(1, '无效的商户', []);
        }

        $money =  abs($request->input('money', 0));
        if ($money == 0) {
            return get_response_message(1, '无效的金额', []);
        }

        if ($money < 1000) {
            return get_response_message(1, '至少1000金额', []);
        }

        if ($merchant->balance < $money) {
            return get_response_message(1, '余额不够'.$money, []);
        }
        try {
            AccountService::innerTransfer($merchant, $targetMerchant, $money);

            return get_response_message(0, 'Success '.$money, []);
        } catch (\Throwable $e) {
            return get_response_message(1, $e->getMessage(), []);
        }
    }
}
