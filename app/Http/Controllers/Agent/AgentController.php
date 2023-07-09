<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Merchant;
use App\Models\MerchatOrderStatistics;
use App\Models\PayinOrder;
use App\Models\PayoutOrder;

class AgentController extends Controller
{
    public function info()
    {
        $agentList               = Agent::where('agentid', auth('agent')->user()->agentid)->first()->toArray();
        $agentList['status']     = Agent::status($agentList['status']);

        return view('agent.agent.info', compact('agentList'));
    }

    public function mymerchant()
    {
        $list = Merchant::query()
            ->where('agent_id', auth('agent')->user()->id)->orderByDesc('id')->get();

        return view('agent.agent.mymerchant', compact('list'));
    }

    public function payinorder()
    {
        $query = PayinOrder::query()->where('agent_id', auth('agent')->user()->id);

        $where   = [];
        $whereIn = [];
        $input   = request()->all();
        if (request()->filled('order_number')) {
            $where[] = ['order_number', '=', $input['order_number']];
        }

        $payInChannelIdFill = request()->filled('payin_channel_id');
        if ($payInChannelIdFill) {
            $where[] = ['payin_channel_id', '=', $input['payin_channel_id']];
        }

        if (request()->filled('status')) {
            $where[] = ['status', '=', $input['status']];
        }

        if (request()->filled('merchantid')) {
            $where[] = ['merchantid', '=', $input['merchantid']];
        }

        if (request()->filled('identity')) {
            $where[] = ['identity', '=', $input['identity']];
        }

        if (request()->filled('out_trade_no')) {
            $where[] = ['out_trade_no', '=', $input['out_trade_no']];
        }

        if (request()->filled('utr')) {
            $where[] = ['utr', '=', $input['utr']];
        }

        if (request()->filled('create_date')) {
            list($startDate, $endDate) = [request()->input('create_date'), request()->input('create_date')];
            $query                     = $query->whereBetween('create_time', [get_day_start_time(strtotime($startDate)), get_day_end_time(strtotime($endDate))]);
        }

        if (request()->filled('customer_name')) {
            $where[] = ['customer_name', 'like', '%'.$input['customer_name'].'%'];
        }

        if (request()->filled('customer_mobile')) {
            $where[] = ['customer_mobile', 'like', '%'.$input['customer_mobile'].'%'];
        }

        if (request()->filled('customer_email')) {
            $where[] = ['customer_email', 'like', '%'.$input['customer_email'].'%'];
        }

        if (request()->filled('notify_status')) {
            $where[] = ['notify_status', '=', $input['notify_status']];
        }

        if (request()->filled('upstream_other_order_number')) {
            $where[] = ['upstream_other_order_number', 'like', '%'.$input['upstream_other_order_number'].'%'];
        }

        if (request()->filled('upstream_time')) {
            list($startDate, $endDate) = explode('|', request()->input('upstream_time'));
            $where[]                   = ['upstream_time', '>=', get_day_start_time(strtotime($startDate))];
            $where[]                   = ['upstream_time', '<', get_day_end_time(strtotime($endDate))];
        }

        if (request()->filled('settlement_time')) {
            list($startDate, $endDate) = explode('|', request()->input('settlement_time'));
            //$where[] = array('settlement_time', '>=', get_day_start_time(strtotime($startDate)));
            //$where[] = array('settlement_time', '<', get_day_end_time(strtotime($endDate)));
            $where[] = ['settlement_time', '>=', strtotime($startDate)];
            $where[] = ['settlement_time', '<', strtotime($endDate)];
        }

        if (request()->filled('order_type') && request()->filled('orders')) {
            $ordes     = explode("\n", request()->input('orders'));
            $field     = request()->input('order_type');
            $whereIn[] = ['key' => $field, 'value' => $ordes];
        }

        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();

        $notifyStatus = ['未通知', '通知成功', '通知失败'];

        foreach ($list as $k => $v) {
            $v->status          = PayinOrder::status($v->status);
            $v->is_notify       = PayinOrder::isNotify($v->is_notify);
            $v->settlement_time = date('Y-m-d H:i:s', $v->settlement_time);
            $v->create_time     = date('Y-m-d H:i:s', $v->create_time);
            $v->upstream_time   = empty($v->upstream_time) ? '无' : date('Y-m-d H:i:s', $v->upstream_time);
            $v->notify_status   = $notifyStatus[$v->notify_status];
        }

        return view('agent.agent.payinorder', compact('list', 'paginator'));
    }

    public function payoutorder()
    {
        $query = PayoutOrder::query();
        $query = $query->where('agent_id', auth('agent')->user()->id);

        if (request()->filled('order_number')) {
            $query = $query->where('order_number', '=', request()->input('order_number'));
        }

        if (request()->filled('status')) {
            $query = $query->where('status', '=', request()->input('status'));
        }

        if (request()->filled('merchantid')) {
            $query = $query->where('merchantid', '=', request()->input('merchantid'));
        }

        if (request()->filled('out_trade_no')) {
            $query = $query->where('out_trade_no', '=', request()->input('out_trade_no'));
        }

        if (request()->filled('create_date')) {
            list($startDate, $endDate) = [request()->input('create_date'), request()->input('create_date')];
            $query                     = $query->whereBetween('create_time', [get_day_start_time(strtotime($startDate)), get_day_end_time(strtotime($endDate))]);
        }

        if (request()->filled('upstream_time')) {
            list($startDate, $endDate) = explode('|', request()->input('upstream_time'));
            $query                     = $query->whereBetween('upstream_time', [get_day_start_time(strtotime($startDate)), get_day_end_time(strtotime($endDate))]);
        }

        if (request()->filled('upstream_create_time')) {
            list($startDate, $endDate) = explode('|', request()->input('upstream_create_time'));
            $query                     = $query->whereBetween('upstream_create_time', [get_day_start_time(strtotime($startDate)), get_day_end_time(strtotime($endDate))]);
        }

        if (request()->filled('customer_name')) {
            $query = $query->where('customer_name', 'like', '%'.request()->input('customer_name').'%');
        }

        if (request()->filled('customer_mobile')) {
            $query = $query->where('customer_mobile', 'like', '%'.request()->input('customer_mobile').'%');
        }

        if (request()->filled('customer_email')) {
            $query = $query->where('customer_email', 'like', '%'.request()->input('customer_email').'%');
        }

        if (request()->filled('upstream_order_number')) {
            $query = $query->where('upstream_order_number', 'like', '%'.request()->input('upstream_order_number').'%');
        }

        if (request()->filled('notify_status')) {
            $query = $query->where('notify_status', '=', request()->input('notify_status'));
        }

        if (request()->filled('notify_times')) {
            $query = $query->where('notify_times', '=', request()->input('notify_times'));
        }
        if (request()->filled('utr')) {
            $query = $query->where('utr', '=', request()->input('utr'));
        }
        if (request()->filled('order_type') && request()->filled('orders')) {
            $ordes = explode("\n", request()->input('orders'));
            $field = request()->input('order_type');
            $query = $query->whereIn($field, $ordes);
        }

        if (request()->filled('is_exe')) {
            $query = $query->where('is_exe', '=', request()->input('is_exe'));
        }

        if (request()->filled('upi_handle')) {
            $query = $query->where('upi_handle', '=', request()->input('upi_handle'));
        }

        if (request()->filled('account_number')) {
            $query = $query->where('account_number', '=', request()->input('account_number'));
        }

        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();

        $notifyStatus = ['未通知', '通知成功', '通知失败'];

        foreach ($list as $k => $v) {
            $v->status        = PayoutOrder::status($v->status);
            $v->is_notify     = PayoutOrder::isNotify($v->is_notify);
            $v->create_time   = date('Y-m-d H:i:s', $v->create_time);
            $v->upstream_time = empty($v->upstream_time) ? '无' : date('Y-m-d H:i:s', $v->upstream_time);
            $v->notify_status = $notifyStatus[$v->notify_status];
        }

        return view('agent.agent.payoutorder', compact('list', 'paginator'));
    }

    public function merchatPayinLine()
    {
        $query = MerchatOrderStatistics::query();
        $query->where('agent_id', auth('agent')->user()->id);
        $startDate = date('Y-m-d', time() - 24 * 10 * 3600);
        $endDate   = date('Y-m-d');
        $query->whereBetween('days', [$startDate, $endDate]);
        $list          = $query->select(['agent_id', 'merchantid', 'days', 'payin_amount'])->get()->toArray();
        $merchantIds   = array_column($list, 'merchantid');
        $merchantIds   = array_unique($merchantIds);
        $merchantDatas = [];
        foreach ($list as $value) {
            $merchantDatas[$value['merchantid']][strtotime($value['days'])] = $value['payin_amount'];
        }
        $series     = [];
        $xaxis_data = [];
        for ($i = strtotime($startDate); $i < strtotime($endDate); $i += 3600 * 24) {
            $xaxis_data[] = date('Y-m-d', $i);
        }
        foreach ($merchantDatas as $key => $datas) {
            $serieData = [];
            for ($i = strtotime($startDate); $i < strtotime($endDate); $i += 3600 * 24) {
                $serieData[] = $datas[$i] ?? 0;
            }
            $series[] = [
                'name'   => $key,
                'smooth' => true,
                'type'   => 'line',
                'data'   => $serieData,
            ];
        }
        $graphData['series']      = $series;
        $graphData['legend_data'] = array_values($merchantIds);
        $graphData['xaxis_data']  = $xaxis_data;

        return view('agent.agent.merchatpayinline', compact('graphData'));
    }

    public function merchatPayoutLine()
    {
        $query = MerchatOrderStatistics::query();
        $query->where('agent_id', auth('agent')->user()->id);
        $startDate = date('Y-m-d', time() - 24 * 10 * 3600);
        $endDate   = date('Y-m-d');
        $query->whereBetween('days', [$startDate, $endDate]);
        $list          = $query->select(['agent_id', 'merchantid', 'days', 'payout_amount'])->get()->toArray();
        $merchantIds   = array_column($list, 'merchantid');
        $merchantIds   = array_unique($merchantIds);
        $merchantDatas = [];
        foreach ($list as $value) {
            $merchantDatas[$value['merchantid']][strtotime($value['days'])] = $value['payout_amount'];
        }
        $series     = [];
        $xaxis_data = [];
        for ($i = strtotime($startDate); $i < strtotime($endDate); $i += 3600 * 24) {
            $xaxis_data[] = date('Y-m-d', $i);
        }
        foreach ($merchantDatas as $key => $datas) {
            $serieData = [];
            for ($i = strtotime($startDate); $i < strtotime($endDate); $i += 3600 * 24) {
                $serieData[] = $datas[$i] ?? 0;
            }
            $series[] = [
                'name'   => $key,
                'smooth' => true,
                'type'   => 'line',
                'data'   => $serieData,
            ];
        }
        $graphData['series']      = $series;
        $graphData['legend_data'] = array_values($merchantIds);
        $graphData['xaxis_data']  = $xaxis_data;

        return view('agent.agent.merchatpayoutline', compact('graphData'));
    }
}
