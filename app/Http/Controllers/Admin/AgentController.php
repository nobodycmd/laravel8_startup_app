<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;

class AgentController extends Controller
{
    public function index()
    {
        $query = Agent::query();

        if (request()->filled('agentid')) {
            $query = $query->where('agentid', 'like', '%'.request()->input('agentid').'%');
        }

        if (request()->filled('name')) {
            $query = $query->where('name', 'like', '%'.request()->input('name').'%');
        }

        if (request()->filled('status')) {
            $query = $query->where('status', '=', request()->input('status'));
        }

        $page  = request()->input('page', 1);
        $limit = request()->input('limit', 10);

        $paginator = $query->paginate($limit);
        $list      = $query->offset(($page - 1) * $limit)->limit($limit)->orderByDesc('id')->get();

        return view('admin.agent.index', compact('paginator', 'list'));
    }

    public function create()
    {
        if (request()->isMethod('get')) {
            $agentList = Agent::select('id', 'name')->where('pid', 0)->orderBy('create_time', 'desc')->get()->toArray();

            return view('admin.agent.create', compact('agentList'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name'             => 'bail|required|unique:agents',
                'payin_poundage1'  => 'bail|required|numeric|min:0',
                'payin_poundage2'  => 'bail|required|numeric|min:0',
                'payout_poundage1' => 'bail|required|numeric|min:0',
                'payout_poundage2' => 'bail|required|numeric|min:0',
                'gc'               => 'bail|required',
            ], [
                'name.required'             => '代理名称不能为空',
                'name.unique'               => '代理名称已存在',
                'payin_poundage1.required'  => '代收手续费不能为空',
                'payin_poundage1.numeric'   => '代收手续费必须为大于等于0的数',
                'payin_poundage1.min'       => '代收手续费必须为大于等于0的数',
                'payin_poundage2.required'  => '代收手续费不能为空',
                'payin_poundage2.numeric'   => '代收手续费必须为大于等于0的数',
                'payin_poundage2.min'       => '代收手续费必须为大于等于0的数',
                'payout_poundage1.required' => '代付手续费不能为空',
                'payout_poundage1.numeric'  => '代付手续费必须为大于等于0的数',
                'payout_poundage1.min'      => '代付手续费必须为大于等于0的数',
                'payout_poundage2.required' => '代付手续费不能为空',
                'payout_poundage2.numeric'  => '代付手续费必须为大于等于0的数',
                'payout_poundage2.min'      => '代付手续费必须为大于等于0的数',
                'gc.required'               => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $agentData                         = [];
            $agentData['agentid']              = get_identity('A');
            $agentData['pid']                  = $input['pid'];
            $agentData['name']                 = $input['name'];
            $agentData['remark']               = empty($input['remark']) ? '' : $input['remark'];
            $agentData['payin_poundage']       = $input['payin_poundage1'].'+'.$input['payin_poundage2'];
            $agentData['payout_poundage']      = $input['payout_poundage1'].'+'.$input['payout_poundage2'];
            $agentData['status']               = $input['status'];
            $agentData['password']             = bcrypt('123456'.config('config.secret_key'));
            $agentData['remember_token']       = '';
            $agentData['google_authenticator'] = get_google_authenticator();
            $agentData['create_time']          = time();
            $agentData['update_time']          = time();
            $agentResult                       = Agent::insertGetId($agentData);
            if (! $agentResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function edit()
    {
        if (request()->isMethod('get')) {
            $agentList                                                 = Agent::select('id', 'name')->where('pid', 0)->where('id', '<>', request()->input('id'))->orderBy('create_time', 'desc')->get()->toArray();
            $list                                                      = Agent::where('id', request()->input('id'))->first()->toArray();
            list($list['payin_poundage1'], $list['payin_poundage2'])   = explode('+', $list['payin_poundage']);
            list($list['payout_poundage1'], $list['payout_poundage2']) = explode('+', $list['payout_poundage']);

            return view('admin.agent.edit', compact('agentList', 'list'));
        } else {
            $input = request()->all();

            $validator = validator($input, [
                'name'             => 'bail|required|unique:agents,name,'.$input['id'],
                'payin_poundage1'  => 'bail|required|numeric|min:0',
                'payin_poundage2'  => 'bail|required|numeric|min:0',
                'payout_poundage1' => 'bail|required|numeric|min:0',
                'payout_poundage2' => 'bail|required|numeric|min:0',
                'gc'               => 'bail|required',
            ], [
                'name.required'             => '代理名称不能为空',
                'name.unique'               => '代理名称已存在',
                'payin_poundage1.required'  => '代收手续费不能为空',
                'payin_poundage1.numeric'   => '代收手续费必须为大于等于0的数',
                'payin_poundage1.min'       => '代收手续费必须为大于等于0的数',
                'payin_poundage2.required'  => '代收手续费不能为空',
                'payin_poundage2.numeric'   => '代收手续费必须为大于等于0的数',
                'payin_poundage2.min'       => '代收手续费必须为大于等于0的数',
                'payout_poundage1.required' => '代付手续费不能为空',
                'payout_poundage1.numeric'  => '代付手续费必须为大于等于0的数',
                'payout_poundage1.min'      => '代付手续费必须为大于等于0的数',
                'payout_poundage2.required' => '代付手续费不能为空',
                'payout_poundage2.numeric'  => '代付手续费必须为大于等于0的数',
                'payout_poundage2.min'      => '代付手续费必须为大于等于0的数',
                'gc.required'               => '谷歌校验码不能为空',
            ]);

            if ($validator->fails()) {
                return get_response_message(1, $validator->errors()->first(), []);
            }

            if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, $input['gc'])) {
                return get_response_message(1, '谷歌校验码错误', []);
            }

            $agentData                    = [];
            $agentData['pid']             = $input['pid'];
            $agentData['name']            = $input['name'];
            $agentData['remark']          = empty($input['remark']) ? '' : $input['remark'];
            $agentData['payin_poundage']  = $input['payin_poundage1'].'+'.$input['payin_poundage2'];
            $agentData['payout_poundage'] = $input['payout_poundage1'].'+'.$input['payout_poundage2'];
            $agentData['status']          = $input['status'];
            $agentData['update_time']     = time();
            $agentResult                  = Agent::where('id', $input['id'])->update($agentData);
            if (! $agentResult) {
                return get_response_message(1, config('config.error'), []);
            }

            return get_response_message(0, '保存成功', []);
        }
    }

    public function password()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $agentData                = [];
        $agentData['password']    = bcrypt('123456'.config('config.secret_key'));
        $agentData['update_time'] = time();
        $agentResult              = Agent::where('id', request()->input('id'))->update($agentData);
        if (! $agentResult) {
            return get_response_message(1, config('config.error'), []);
        }

        return get_response_message(0, '重置密码成功，默认密码为：123456', []);
    }

    public function googleauthenticator()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        $googleAuthenticator = Agent::where('id', request()->input('id'))->value('google_authenticator');

        return get_response_message(0, '查看成功', ['google_authenticator' => $googleAuthenticator]);
    }

    public function quicklogin()
    {
        if (! get_google_authenticator_checkcode(auth('admin')->user()->google_authenticator, request()->input('gc'))) {
            return get_response_message(1, '谷歌校验码错误', []);
        }

        auth('agent')->loginUsingId(request()->input('id'), true);

        return get_response_message(0, '登陆成功', ['quickloginurl' => route('agent.index.login')]);
    }
}
