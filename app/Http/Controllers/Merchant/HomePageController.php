<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\PayinOrder;
use App\Models\PayoutOrder;

class HomePageController extends Controller
{
    public function console()
    {
        $time                                      = time();
        $dayStartTime                              = get_day_start_time($time);
        $dayEndTime                                = get_day_end_time($time);
        $yesterdayStartTime                        = $dayStartTime - 86400;
        $yesterdayEndTime                          = $dayEndTime - 86400;
        $list                                      = [];
        $merchantId                                = auth('merchant')->user()->merchantid;
        $list['yesterday_payin_total_fee_success'] = PayinOrder::whereBetween('create_time', [$yesterdayStartTime, $yesterdayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->sum('total_fee');
        $list['payin_total_fee_payin_success']     = PayinOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->sum('total_fee');
        $list['payin_total_fee']                   = PayinOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->sum('total_fee');
        $list['payin_total_fee_fail']              = PayinOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_FAIL)->sum('total_fee');

        $total                                     = $list['payin_total_fee_fail'] + $list['payin_total_fee_payin_success'];
        $list['payin_total_fee_percentage']        = $list['payin_total_fee_payin_success'] == 0 ? '0%' : bcmul(bcdiv($list['payin_total_fee_payin_success'], $total, 2), 100, 0).'%';

        $list['yesterday_payin_count_success'] = PayinOrder::whereBetween('create_time', [$yesterdayStartTime, $yesterdayEndTime])->where('merchantid', $merchantId)->where('status', 'payin_success')->count();
        $list['payin_count_payin_success']     = PayinOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->count();
        $list['payin_count']                   = PayinOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->count();
        $list['payin_count_fail']              = PayinOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_FAIL)->count();
        $totalOrder                            = $list['payin_count_fail'] + $list['payin_count_payin_success'];
        $list['payin_count_percentage']        = $list['payin_count_payin_success'] == 0 ? '0%' : bcmul(bcdiv($list['payin_count_payin_success'], $totalOrder, 2), 100, 0).'%';

        $list['yesterday_payout_total_fee_success'] = PayoutOrder::whereBetween('create_time', [$yesterdayStartTime, $yesterdayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->sum('total_fee');
        $list['payout_total_fee_payout_success']    = PayoutOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->sum('total_fee');
        $list['payout_total_fee']                   = PayoutOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->sum('total_fee');
        $list['payout_total_fee_percentage']        = $list['payout_total_fee'] == 0 ? '0%' : bcmul(bcdiv($list['payout_total_fee_payout_success'], $list['payout_total_fee'], 2), 100, 0).'%';

        $list['yesterday_payout_count_success'] = PayoutOrder::whereBetween('create_time', [$yesterdayStartTime, $yesterdayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->count();
        $list['payout_count_payout_success']    = PayoutOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->where('status', PayinOrder::STATUS_SUCCESS)->count();
        $list['payout_count']                   = PayoutOrder::whereBetween('create_time', [$dayStartTime, $dayEndTime])->where('merchantid', $merchantId)->count();
        $list['payout_count_percentage']        = $list['payout_count'] == 0 ? '0%' : bcmul(bcdiv($list['payout_count_payout_success'], $list['payout_count'], 2), 100, 0).'%';

        return view('merchant.homepage.console', compact('list'));
    }
}
