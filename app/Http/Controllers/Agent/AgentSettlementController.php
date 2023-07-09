<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentSettlementNew;

class AgentSettlementController extends Controller
{
    public function index()
    {
        $limit = request()->input('limit', 50);
        $page  = request()->input('page', 1);
        $query = AgentSettlementNew::query();
        $query->where('agent_id', '=', auth('agent')->user()->id);

        if ($v = request()->input('id', '')) {
            $query->where('id', '=', $v);
        }

        if ($v = request()->input('in_total_fee', '')) {
            $query->where('in_total_fee', '=', $v);
        }
        if ($v = request()->input('in_count', '')) {
            $query->where('in_count', '=', $v);
        }
        if ($v = request()->input('in_settlement_fee', '')) {
            $query->where('in_settlement_fee', '=', $v);
        }
        if ($v = request()->input('order_create_time', '')) {
            $query->where('order_create_time', '=', $v);
        }
        if ($v = request()->input('create_time', '')) {
            $query->where('create_time', '=', $v);
        }
        if ($v = request()->input('update_time', '')) {
            $query->where('update_time', '=', $v);
        }
        if ($v = request()->input('out_total_fee', '')) {
            $query->where('out_total_fee', '=', $v);
        }
        if ($v = request()->input('out_count', '')) {
            $query->where('out_count', '=', $v);
        }
        if ($v = request()->input('out_settlement_fee', '')) {
            $query->where('out_settlement_fee', '=', $v);
        }
        if ($v = request()->input('is_manual', '')) {
            $query->where('is_manual', '=', $v);
        }
        if ($v = request()->input('order_number', '')) {
            $query->where('order_number', '=', $v);
        }
        if ($v = request()->input('merchantid', '')) {
            $query->where('merchantid', '=', $v);
        }
        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();

        return view('agent.agentsettlement.index', compact('paginator', 'list'));
    }

    public function export()
    {
        ini_set('max_execution_time', 600);
        $query = AgentSettlementNew::query();

        $query = $query->where('agent_id', auth('agent')->user()->id);

        if (request()->filled('order_create_time')) {
            list($startDate, $endDate) = explode('|', request()->input('order_create_time'));
            $query                     = $query->whereBetween('order_create_time', [get_day_start_time(strtotime($startDate)), get_day_end_time(strtotime($endDate))]);
        }
        $agentList = Agent::query()->get()->toArray();
        $agent     = [];
        foreach ($agentList as $a) {
            $agent[$a['id']] = $a;
        }

        $letterTitle = [
            'A' => '代理号',
            'B' => '代理名称',
            'C' => '代收交易金额',
            'D' => '代收订单数',
            'E' => '代收结算金额',
            'F' => '代付交易金额',
            'G' => '代付订单数',
            'H' => '代付结算金额',
            'I' => '总结算金额',
            'J' => '订单创建日期',
        ];
        $maxId = 0;
        $limit = 1000;
        $title = '代理结算';
        header('Content-type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:attachment;filename='.$title.'.csv');

        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // 添加 BOM
        fputcsv($fp, $letterTitle);

        while (true) {
            $list     = [];
            $newQuery = clone $query;
            $list     = $newQuery
                ->where('id', '>', $maxId)
                ->limit($limit)
                ->orderBy('id')
                ->get();
            if ($list->isEmpty()) {
                break;
            }
            $maxId = $list->max(['id']);
            if ($list) {
                foreach ($list as $k => $v) {
                    $list[$k]['order_create_time'] = date('Y-m-d', $v['order_create_time']);
                    $list[$k]['agent_name']        = $agent[$v['agent_id']]['name'];
                    $list[$k]['agentid']           = $agent[$v['agent_id']]['agentid'];
                    $list[$k]['settlement_fee']    = bcadd($v['in_settlement_fee'], $v['out_settlement_fee'], 2);
                    $tempExportData                = [];
                    $tempExportData['A']           = $list[$k]['agentid'];
                    $tempExportData['B']           = $list[$k]['agent_name'];
                    $tempExportData['C']           = $list[$k]['in_total_fee'];
                    $tempExportData['D']           = $list[$k]['in_count'];
                    $tempExportData['E']           = $list[$k]['in_settlement_fee'];
                    $tempExportData['F']           = $list[$k]['out_total_fee'];
                    $tempExportData['G']           = $list[$k]['out_count'];
                    $tempExportData['H']           = $list[$k]['out_settlement_fee'];
                    $tempExportData['I']           = $list[$k]['settlement_fee'];
                    $tempExportData['J']           = $list[$k]['order_create_time'];

                    fputcsv($fp, $tempExportData);
                }
                ob_flush();
                flush();
            }
        }
        fclose($fp);
        die;
    }
}
