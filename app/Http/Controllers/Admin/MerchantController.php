<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Journal;
use App\Models\Merchant;
use App\Models\PayinChannel;
use App\Models\PayoutChannel;
use App\Services\AccountService;
use App\Services\PayoutOrderDetailService;
use App\Services\RedisService;

class MerchantController extends Controller
{
    public function index()
    {
        $query = Merchant::query();
        $query = $query->where('is_delete', '=', '2');

        if (request()->filled('agentid')) {
            $query = $query->where('agentid', 'like', '%'.request()->input('agentid').'%');
        }

        if (request()->filled('merchantid')) {
            $query = $query->where('merchantid', 'like', '%'.request()->input('merchantid').'%');
        }

        if (request()->filled('name')) {
            $query = $query->where('name', 'like', '%'.request()->input('name').'%');
        }

        if (request()->filled('status')) {
            $query = $query->where('status', '=', request()->input('status'));
        }

        if (request()->filled('type')) {
            $type  = request()->input('type');
            $query = $query->where('type', '=', $type);
        }

        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->orderBy('id', 'desc')->limit($limit)->get();

        return view('admin.merchant.index', compact('paginator', 'list'));
    }

    public function create()
    {
        if (request()->isMethod('get')) {
            /** @var \App\Models\Agent[] $agentList */
            $agentList = Agent::orderBy('create_time', 'desc')->get();
            foreach ($agentList as $one) {
                $one->name .= '收'.$one->payin_poundage.' 付'.$one->payout_poundage;
            }
            $payinchannelList  = PayinChannel::select('id', 'name')->where('is_delete', 2)->orderBy('create_time', 'desc')->get()->toArray();
            $payoutchannelList = PayoutChannel::select('id', 'name')->where('is_delete', 2)->orderBy('create_time', 'desc')->get()->toArray();

            return view('admin.merchant.create', compact('agentList', 'payinchannelList', 'payoutchannelList'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name'                       => 'bail|required|unique:merchants',
                'identity'                   => 'bail|required',
                'settlement_cycle'           => 'bail|required|numeric|min:0',
                'payinsettlement_proportion' => 'bail|required',
                'payin_poundage1'            => 'bail|required|numeric|min:0',
                'payin_poundage2'            => 'bail|required|numeric|min:0',
                'manual_payin_poundage1'     => 'bail|required|numeric|min:0',
                'manual_payin_poundage2'     => 'bail|required|numeric|min:0',
                'payin_limit'                => 'bail|required|numeric|min:0',
                'in_min_amount'              => 'bail|required|numeric|min:0',
                'in_max_amount'              => 'bail|required|numeric|min:0',
                'payout_poundage1'           => 'bail|required|numeric|min:0',
                'payout_poundage2'           => 'bail|required|numeric|min:0',
                'payout_trigger'             => 'bail|required|numeric|min:0',
                'payout_limit'               => 'bail|required|numeric|min:0',
                'out_min_amount'             => 'bail|required|numeric|min:0',
                'out_max_amount'             => 'bail|required|numeric|min:0',
                'request_ip'                 => 'bail|required',
                'request_domain'             => 'bail|required',
                'gc'                         => 'bail|required',
                'url'                        => 'bail|required',
                'payinsettlement_remark'     => 'bail|required',
            ], [
                'name.required'                       => '商户名称不能为空',
                'name.unique'                         => '商户名称已存在',
                'identity.required'                   => '统一引用不能为空',
                'settlement_cycle.required'           => '结算周期不能为空',
                'settlement_cycle.numeric'            => '结算周期必须为大于等于0的数',
                'settlement_cycle.min'                => '结算周期必须为大于等于0的数',
                'payinsettlement_proportion.required' => '结算下发比例必须保留两位小数',
                'payin_poundage1.required'            => '代收手续费不能为空',
                'payin_poundage1.numeric'             => '代收手续费必须为大于等于0的数',
                'payin_poundage1.min'                 => '代收手续费必须为大于等于0的数',
                'payin_poundage2.required'            => '代收手续费不能为空',
                'payin_poundage2.numeric'             => '代收手续费必须为大于等于0的数',
                'payin_poundage2.min'                 => '代收手续费必须为大于等于0的数',
                'manual_payin_poundage1.required'     => '手动代收手续费不能为空',
                'manual_payin_poundage1.numeric'      => '手动代收手续费必须为大于等于0的数',
                'manual_payin_poundage1.min'          => '手动代收手续费必须为大于等于0的数',
                'manual_payin_poundage2.required'     => '手动代收手续费不能为空',
                'manual_payin_poundage2.numeric'      => '手动代收手续费必须为大于等于0的数',
                'manual_payin_poundage2.min'          => '手动代收手续费必须为大于等于0的数',
                'payin_limit.required'                => '代收限额不能为空',
                'payin_limit.numeric'                 => '代收限额必须为大于等于0的数',
                'payin_limit.min'                     => '代收限额必须为大于等于0的数',
                'in_min_amount.required'              => '代收最低金额不能为空',
                'in_min_amount.numeric'               => '代收最低金额必须为大于等于0的数',
                'in_min_amount.min'                   => '代收最低金额必须为大于等于0的数',
                'in_max_amount.required'              => '代收最高金额不能为空',
                'in_max_amount.numeric'               => '代收最高金额必须为大于等于0的数',
                'in_max_amount.min'                   => '代收最高金额必须为大于等于0的数',
                'payout_poundage1.required'           => '代付手续费不能为空',
                'payout_poundage1.numeric'            => '代付手续费必须为大于等于0的数',
                'payout_poundage1.min'                => '代付手续费必须为大于等于0的数',
                'payout_poundage2.required'           => '代付手续费不能为空',
                'payout_poundage2.numeric'            => '代付手续费必须为大于等于0的数',
                'payout_poundage2.min'                => '代付手续费必须为大于等于0的数',
                'payout_trigger.min'                  => '代付金额多少后不收每笔手续费必须为大于等于0的数',
                'payout_limit.required'               => '代付限额不能为空',
                'payout_limit.numeric'                => '代付限额必须为大于等于0的数',
                'payout_limit.min'                    => '代付限额必须为大于等于0的数',
                'out_min_amount.required'             => '代付最低金额不能为空',
                'out_min_amount.numeric'              => '代付最低金额必须为大于等于0的数',
                'out_min_amount.min'                  => '代付最低金额必须为大于等于0的数',
                'out_max_amount.required'             => '代付最高金额不能为空',
                'out_max_amount.numeric'              => '代付最高金额必须为大于等于0的数',
                'out_max_amount.min'                  => '代付最高金额必须为大于等于0的数',
                'request_ip.required'                 => '报备IP不能为空',
                'request_domain.required'             => '报备域名不能为空',
                'gc.required'                         => '谷歌校验码不能为空',
                'url.required'                        => '商户官网不能为空',
                'payinsettlement_remark.required'     => '下发备注不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (strval($input['payinsettlement_proportion']) !== strval(bcmul($input['payinsettlement_proportion'], 1, 2))) {
                return get_response_message(1, '结算下发比例必须保留两位小数', []);
            }

            if ($input['payinsettlement_proportion'] <= 0) {
                return get_response_message(1, '结算下发比例必须为大于0的数', []);
            }

            if ($input['in_max_amount'] <= $input['in_min_amount']) {
                return get_response_message(1, '代收最高限额必须大于代收最低限额', []);
            }
            if ($input['out_max_amount'] <= $input['out_min_amount']) {
                return get_response_message(1, '代付最高限额必须大于代付最低限额', []);
            }

            if ($input['agent_id'] > 0) {
                if (! is_numeric($input['agent_in_fee1']) || ! is_numeric($input['agent_in_fee2'])) {
                    return get_response_message(1, '代理的代收费率必须都是数字', []);
                }
                if (! is_numeric($input['agent_out_fee1']) || ! is_numeric($input['agent_out_fee2'])) {
                    return get_response_message(1, '代理的代付费率必须都是数字', []);
                }
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $lock = RedisService::lock('create_merchant', 2);
            if (! $lock) {
                return get_response_message(1, '其他人正在创建商户，过2秒后再试', []);
            }

            $merchantData                               = [];
            $merchantData['merchantid']                 = date('ymdHis'); //230425080343
            $merchantData['secretkey']                  = md5(uniqid().$merchantData['merchantid']);
            $merchantData['password']                   = bcrypt('123456'.config('config.secret_key'));
            $merchantData['balance']                    = '0.00';
            $merchantData['remember_token']             = '';
            $merchantData['agent_id']                   = $input['agent_id'];
            $merchantData['name']                       = $input['name'];
            $merchantData['identity']                   = $input['identity'];
            $merchantData['settlement_cycle']           = $input['settlement_cycle'];
            $merchantData['payinsettlement_proportion'] = $input['payinsettlement_proportion'];
            $merchantData['status']                     = $input['status'];
            $merchantData['payin_poundage']             = $input['payin_poundage1'].'+'.$input['payin_poundage2'];
            $merchantData['manual_payin_poundage']      = $input['manual_payin_poundage1'].'+'.$input['manual_payin_poundage2'];
            $merchantData['payin_limit']                = $input['payin_limit'];
            $merchantData['payin_status']               = $input['payin_status'];
            $merchantData['payin_is_cycle']             = $input['payin_is_cycle'];
            $merchantData['payin_channel_id']           = $input['payin_channel_id'];
            $merchantData['in_min_amount']              = $input['in_min_amount'];
            $merchantData['in_max_amount']              = $input['in_max_amount'];
            $merchantData['payout_poundage']            = $input['payout_poundage1'].'+'.$input['payout_poundage2'];
            $merchantData['payout_trigger']             = intval($input['payout_trigger']) == 0 ? 0 : $input['payout_trigger'];
            $merchantData['payout_limit']               = $input['payout_limit'];
            $merchantData['payout_status']              = $input['payout_status'];
            $merchantData['payout_is_cycle']            = $input['payout_is_cycle'];
            $merchantData['payout_channel_id']          = $input['payout_channel_id'];
            $merchantData['out_min_amount']             = $input['out_min_amount'];
            $merchantData['out_max_amount']             = $input['out_max_amount'];
            $merchantData['is_delete']                  = '2';
            $merchantData['google_authenticator']       = get_google_authenticator();
            $merchantData['request_ip']                 = $input['request_ip'];
            $merchantData['request_domain']             = $input['request_domain'];
            $merchantData['type']                       = $input['type'];
            $merchantData['url']                        = $input['url'];
            $merchantData['payinsettlement_remark']     = $input['payinsettlement_remark'];
            $merchantData['create_time']                = time();
            $merchantData['update_time']                = time();
            $merchantData['agent_in_fee']               = $input['agent_id'] > 0 ? $input['agent_in_fee1'].'+'.$input['agent_in_fee2'] : '0+0';
            $merchantData['agent_out_fee']              = $input['agent_id'] > 0 ? $input['agent_out_fee1'].'+'.$input['agent_out_fee2'] : '0+0';
            $merchantData['allow_manual_payout']        = $input['allow_manual_payout'];
            $merchantData['tg_id']                      = $input['tg_id'] ?? 0;

            $merchantData['in_sorting_channel_group'] = $input['in_sorting_channel_group'];
            if ($merchantData['payin_is_cycle'] == 3 && $merchantData['in_sorting_channel_group'] == 0) {
                return get_response_message(1, '选择代收组', []);
            }
            if ($merchantData['payin_is_cycle'] != 3) {
                $merchantData['in_sorting_channel_group'] = 0;
            }

            $merchantData['out_sorting_channel_group'] = $input['out_sorting_channel_group'];
            if ($merchantData['payout_is_cycle'] == 3 && $merchantData['out_sorting_channel_group'] == 0) {
                return get_response_message(1, '选择代付组', []);
            }
            if ($merchantData['payout_is_cycle'] != 3) {
                $merchantData['out_sorting_channel_group'] = 0;
            }

            $merchantResult = Merchant::insertGetId($merchantData);
            if (! $merchantResult) {
                return get_response_message(1, '保存失败', []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function edit()
    {
        if (request()->isMethod('get')) {
            $agentList                                                 = Agent::select('id', 'name')->orderBy('create_time', 'desc')->get()->toArray();
            $payinchannelList                                          = PayinChannel::select('id', 'name')->where('is_delete', 2)->orderBy('create_time', 'desc')->get()->toArray();
            $payoutchannelList                                         = PayoutChannel::select('id', 'name')->where('is_delete', 2)->orderBy('create_time', 'desc')->get()->toArray();
            $list                                                      = Merchant::where('id', request()->input('id'))->first();
            list($list['payin_poundage1'], $list['payin_poundage2'])   = explode('+', $list['payin_poundage']);
            list($list['payout_poundage1'], $list['payout_poundage2']) = explode('+', $list['payout_poundage']);
            if ($list['manual_payin_poundage']) {
                list($list['manual_payin_poundage1'], $list['manual_payin_poundage2']) = explode('+', $list['manual_payin_poundage']);
            } else {
                $list['manual_payin_poundage1'] = 0;
                $list['manual_payin_poundage2'] = 0;
            }
            list($list['agent_in_fee1'], $list['agent_in_fee2'])   = explode('+', $list['agent_in_fee']);
            list($list['agent_out_fee1'], $list['agent_out_fee2']) = explode('+', $list['agent_out_fee']);
            $list['payin_is_cycle']                                = ! in_array($list['payin_is_cycle'], [1, 2, 3]) ? 1 : $list['payin_is_cycle'];
            $list['payout_is_cycle']                               = ! in_array($list['payout_is_cycle'], [1, 2, 3]) ? 1 : $list['payout_is_cycle'];

            return view('admin.merchant.edit', compact('agentList', 'payinchannelList', 'payoutchannelList', 'list'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name'                       => 'bail|required|unique:merchants,name,'.$input['id'],
                'identity'                   => 'bail|required|unique:merchants,identity,'.$input['id'],
                'settlement_cycle'           => 'bail|required|numeric|min:0',
                'payinsettlement_proportion' => 'bail|required|numeric',
                'payin_poundage1'            => 'bail|required|numeric|min:0',
                'payin_poundage2'            => 'bail|required|numeric|min:0',
                'manual_payin_poundage1'     => 'bail|required|numeric|min:0',
                'manual_payin_poundage2'     => 'bail|required|numeric|min:0',
                'payin_limit'                => 'bail|required|numeric|min:0',
                'in_min_amount'              => 'bail|required|numeric|min:0',
                'in_max_amount'              => 'bail|required|numeric|min:0',
                'payout_poundage1'           => 'bail|required|numeric|min:0',
                'payout_poundage2'           => 'bail|required|numeric|min:0',
                'payout_trigger'             => 'bail|required|numeric|min:0',
                'payout_limit'               => 'bail|required|numeric|min:0',
                'out_min_amount'             => 'bail|required|numeric|min:0',
                'out_max_amount'             => 'bail|required|numeric|min:0',
                'request_ip'                 => 'bail|required',
                'request_domain'             => 'bail|required',
                'gc'                         => 'bail|required',
                'url'                        => 'bail|required',
                'payinsettlement_remark'     => 'bail|required',
            ], [
                'name.required'                       => '商户名称不能为空',
                'name.unique'                         => '商户名称已存在',
                'identity.required'                   => '商户标识不能为空',
                'identity.unique'                     => '商户标识已存在',
                'settlement_cycle.required'           => '结算周期不能为空',
                'settlement_cycle.numeric'            => '结算周期必须为大于等于0的数',
                'settlement_cycle.min'                => '结算周期必须为大于等于0的数',
                'payinsettlement_proportion.required' => '结算下发比例必须保留两位小数',
                'payin_poundage1.required'            => '代收手续费不能为空',
                'payin_poundage1.numeric'             => '代收手续费必须为大于等于0的数',
                'payin_poundage1.min'                 => '代收手续费必须为大于等于0的数',
                'payin_poundage2.required'            => '代收手续费不能为空',
                'payin_poundage2.numeric'             => '代收手续费必须为大于等于0的数',
                'payin_poundage2.min'                 => '代收手续费必须为大于等于0的数',
                'manual_payin_poundage1.required'     => '手动代收手续费不能为空',
                'manual_payin_poundage1.numeric'      => '手动代收手续费必须为大于等于0的数',
                'manual_payin_poundage1.min'          => '手动代收手续费必须为大于等于0的数',
                'manual_payin_poundage2.required'     => '手动代收手续费不能为空',
                'manual_payin_poundage2.numeric'      => '手动代收手续费必须为大于等于0的数',
                'manual_payin_poundage2.min'          => '手动代收手续费必须为大于等于0的数',
                'payin_limit.required'                => '代收限额不能为空',
                'payin_limit.numeric'                 => '代收限额必须为大于等于0的数',
                'payin_limit.min'                     => '代收限额必须为大于等于0的数',
                'in_min_amount.required'              => '代收最低金额不能为空',
                'in_min_amount.numeric'               => '代收最低金额必须为大于等于0的数',
                'in_min_amount.min'                   => '代收最低金额必须为大于等于0的数',
                'in_max_amount.required'              => '代收最高金额不能为空',
                'in_max_amount.numeric'               => '代收最高金额必须为大于等于0的数',
                'in_max_amount.min'                   => '代收最高金额必须为大于等于0的数',
                'payout_poundage1.required'           => '代付手续费不能为空',
                'payout_poundage1.numeric'            => '代付手续费必须为大于等于0的数',
                'payout_poundage1.min'                => '代付手续费必须为大于等于0的数',
                'payout_poundage2.required'           => '代付手续费不能为空',
                'payout_poundage2.numeric'            => '代付手续费必须为大于等于0的数',
                'payout_poundage2.min'                => '代付手续费必须为大于等于0的数',
                'payout_trigger.min'                  => '代付金额多少后不收每笔手续费必须为大于等于0的数',
                'payout_limit.required'               => '代付限额不能为空',
                'payout_limit.numeric'                => '代付限额必须为大于等于0的数',
                'payout_limit.min'                    => '代付限额必须为大于等于0的数',
                'out_min_amount.required'             => '代付最低金额不能为空',
                'out_min_amount.numeric'              => '代付最低金额必须为大于等于0的数',
                'out_min_amount.min'                  => '代付最低金额必须为大于等于0的数',
                'out_max_amount.required'             => '代付最高金额不能为空',
                'out_max_amount.numeric'              => '代付最高金额必须为大于等于0的数',
                'out_max_amount.min'                  => '代付最高金额必须为大于等于0的数',
                'request_ip.required'                 => '报备IP不能为空',
                'request_domain.required'             => '报备域名不能为空',
                'gc.required'                         => '谷歌校验码不能为空',
                'url.required'                        => '商户官网不能为空',
                'payinsettlement_remark.required'     => '下发备注不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (strval($input['payinsettlement_proportion']) !== strval(bcmul($input['payinsettlement_proportion'], 1, 2))) {
                return get_response_message(1, '结算下发比例必须保留两位小数', []);
            }

            if ($input['payinsettlement_proportion'] <= 0) {
                return get_response_message(1, '结算下发比例必须为大于0的数', []);
            }

            if ($input['payinsettlement_proportion'] > 100) {
                return get_response_message(1, '结算下发比例必须为小于等于100的数', []);
            }

            if ($input['in_max_amount'] <= $input['in_min_amount']) {
                return get_response_message(1, '代收最高限额必须大于代收最低限额', []);
            }
            if ($input['out_max_amount'] <= $input['out_min_amount']) {
                return get_response_message(1, '代付最高限额必须大于代付最低限额', []);
            }
            if ($input['agent_id'] > 0) {
                if (! is_numeric($input['agent_in_fee1']) || ! is_numeric($input['agent_in_fee2'])) {
                    return get_response_message(1, '代理的代收费率必须都是数字', []);
                }
                if (! is_numeric($input['agent_out_fee1']) || ! is_numeric($input['agent_out_fee2'])) {
                    return get_response_message(1, '代理的代付费率必须都是数字', []);
                }
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $merchantData                               = [];
            $merchantData['agent_id']                   = $input['agent_id'];
            $merchantData['name']                       = $input['name'];
            $merchantData['identity']                   = $input['identity'];
            $merchantData['settlement_cycle']           = $input['settlement_cycle'];
            $merchantData['payinsettlement_proportion'] = $input['payinsettlement_proportion'];
            $merchantData['status']                     = $input['status'];
            $merchantData['payin_poundage']             = $input['payin_poundage1'].'+'.$input['payin_poundage2'];
            $merchantData['manual_payin_poundage']      = $input['manual_payin_poundage1'].'+'.$input['manual_payin_poundage2'];
            $merchantData['payin_limit']                = $input['payin_limit'];
            $merchantData['payin_status']               = $input['payin_status'];
            $merchantData['payin_is_cycle']             = $input['payin_is_cycle'];
            $merchantData['payin_channel_id']           = $input['payin_channel_id'];
            $merchantData['in_min_amount']              = $input['in_min_amount'];
            $merchantData['in_max_amount']              = $input['in_max_amount'];
            $merchantData['payout_poundage']            = $input['payout_poundage1'].'+'.$input['payout_poundage2'];
            $merchantData['payout_trigger']             = intval($input['payout_trigger']) == 0 ? 0 : $input['payout_trigger'];
            $merchantData['payout_limit']               = $input['payout_limit'];
            $merchantData['payout_status']              = $input['payout_status'];
            $merchantData['payout_is_cycle']            = $input['payout_is_cycle'];
            $merchantData['payout_channel_id']          = $input['payout_channel_id'];
            $merchantData['out_min_amount']             = $input['out_min_amount'];
            $merchantData['out_max_amount']             = $input['out_max_amount'];
            $merchantData['request_ip']                 = $input['request_ip'];
            $merchantData['request_domain']             = $input['request_domain'];
            $merchantData['update_time']                = time();
            $merchantData['is_settlement']              = $input['is_settlement'];
            $merchantData['type']                       = $input['type'];
            $merchantData['url']                        = $input['url'];
            $merchantData['agent_in_fee']               = $input['agent_id'] > 0 ? $input['agent_in_fee1'].'+'.$input['agent_in_fee2'] : '0+0';
            $merchantData['agent_out_fee']              = $input['agent_id'] > 0 ? $input['agent_out_fee1'].'+'.$input['agent_out_fee2'] : '0+0';
            $merchantData['allow_manual_payout']        = $input['allow_manual_payout'];
            $merchantData['payinsettlement_remark']     = $input['payinsettlement_remark'];
            $merchantData['tg_id']                      = $input['tg_id'] ?? 0;

            $merchantData['in_sorting_channel_group'] = $input['in_sorting_channel_group'];
            if ($merchantData['payin_is_cycle'] == 3 && $merchantData['in_sorting_channel_group'] == 0) {
                return get_response_message(1, '选择代收组', []);
            }
            if ($merchantData['payin_is_cycle'] != 3) {//不是分组
                $merchantData['in_sorting_channel_group'] = 0;
            }

            $merchantData['out_sorting_channel_group'] = $input['out_sorting_channel_group'];
            if ($merchantData['payout_is_cycle'] == 3 && $merchantData['out_sorting_channel_group'] == 0) {
                return get_response_message(1, '选择代付组', []);
            }
            if ($merchantData['payout_is_cycle'] != 3) {
                $merchantData['out_sorting_channel_group'] = 0;
            }

            try {
                $merchantResult = Merchant::where('id', $input['id'])->update($merchantData);
                if (! $merchantResult) {
                    return get_response_message(1, config('config.error'), []);
                }
            } catch (\Exception $e) {
                return get_response_message(1, $e->getMessage(), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function password()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $merchantData                = [];
        $merchantData['password']    = bcrypt('123456'.config('config.secret_key'));
        $merchantData['update_time'] = time();
        $merchantResult              = Merchant::where('id', request()->input('id'))->update($merchantData);
        if (! $merchantResult) {
            return get_response_message(1, config('config.error'), []);
        }

        return get_response_message(0, '重置密码成功，默认密码为：123456', []);
    }

    public function secretkey()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $merchantData                = [];
        $merchantData['secretkey']   = get_unique_identity();
        $merchantData['update_time'] = time();
        $merchantResult              = Merchant::where('id', request()->input('id'))->update($merchantData);
        if (! $merchantResult) {
            return get_response_message(1, config('config.error'), []);
        }

        return get_response_message(0, '重置密钥成功', []);
    }

    public function balance()
    {
        $merchant = Merchant::where('id', request()->input('id'))->first();
        if (request()->isMethod('get')) {
            $merchantAccountLogList = Journal::where('merchantid', $merchant->merchantid)->get();

            return view('admin.merchant.balance', compact('merchant', 'merchantAccountLogList'));
        }

        $validator = validator(request()->all(), [
           'total_fee' => 'required',
           'remark'    => 'required',
        ]);
        if ($validator->fails()) {
            return get_response_message(1, $validator->errors()->first(), []);
        }

        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $remark = $_POST['remark'];
        $remark .= auth('admin')->user()->name.'操作';
        if ($_POST['type'] == 1) {
            AccountService::increase($merchant->merchantid, $_POST['total_fee'], 0, Journal::TYPE_BACKEND, $remark);
        } else {
            AccountService::decrease($merchant->merchantid, $_POST['total_fee'], 0, Journal::TYPE_BACKEND, $remark);
        }

        return apiRender();
    }

    public function googleauthenticator()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $googleAuthenticator = Merchant::where('id', request()->input('id'))->value('google_authenticator');

        return get_response_message(0, "谷歌密钥$googleAuthenticator", []);
    }

    public function quicklogin()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        auth('merchant')->loginUsingId(request()->input('id'), true);

        return get_response_message(0, '登陆成功', ['quickloginurl' => route('merchant.index.login')]);
    }

    public function cancelPayoutFileOrders()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $turnCount = PayoutOrderDetailService::cancelTheMerchantPayoutOrder(request()->input('id'));

        return get_response_message(0, '', [
            'n' => $turnCount,
        ]);
    }
}
