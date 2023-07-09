@extends('layouts.layout')

@section('layout')

    <div class="panel panel-info">
        <div class="panel-heading">创建商户</div>
        <div class="panel-body">


                        <form  action="{{route('admin.merchant.create')}}" name="f" >
                            <input type="hidden" name="payin_channel_id" value="0" />

                            
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                            <tbody>
                            
                            <tr><td>上级代理</td>
                                <td>
                                    <select name="agent_id" id="agent_id"  class="form-control"  >
                                        <option value="0">无</option>
                                        @foreach($agentList as $value)
                                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>代理的代收费率</td>
                                <td>
                                    <input type="text" name="agent_in_fee1" placeholder="百分比" autocomplete="off"  id="agent_in_fee">
                                    +
                                    <input type="text" name="agent_in_fee2" placeholder="每笔" autocomplete="off" >
                                </td>
                            </tr>
                            <tr>
                                <td>代理的代付费率</td>
                                <td>
                                    <input type="text" name="agent_out_fee1" placeholder="百分比" autocomplete="off"  id="agent_out_fee">
                                    +
                                    <input type="text" name="agent_out_fee2" placeholder="每笔" autocomplete="off" >
                                </td>
                            </tr>
                            <tr><td>商户名称</td>
                                <td>
                                    <input type="text" name="name" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>统一引用</td>
                                <td>
                                    <input type="text" name="identity" placeholder="同一个引用下商户才可以相互转账" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>结算周期</td>
                                <td>
                                    <input type="text" name="settlement_cycle" placeholder="D+几" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>结算下发比例(N%)</td>
                                <td>
                                    <input type="text" name="payinsettlement_proportion" value="100.00" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr><td>下发备注</td>
                                <td>
                                    <input type="text" name="payinsettlement_remark" placeholder="" autocomplete="off" class="form-control" >
                                </td>
                            </tr><tr><td>状态</td>
                                <td>
                                    @foreach(\App\Models\Merchant::status() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr><tr><td>代收手续费</td>
                                <td>
                                    <input type="text" name="payin_poundage1" placeholder="百分之几" autocomplete="off" >
                                    +
                                    <input type="text" name="payin_poundage2" placeholder="每笔" autocomplete="off" >
                                </td>
                            </tr><tr><td>手动代收手续费</td>
                                <td>
                                    <input type="text" name="manual_payin_poundage1" placeholder="百分之几" autocomplete="off" >
                                +
                                    <input type="text" name="manual_payin_poundage2" placeholder="每笔" autocomplete="off" >
                                </td>
                            </tr><tr><td>每日代收限额</td>
                                <td>
                                    <input type="text" name="payin_limit" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>代收状态</td>
                                <td>
                                    @foreach(\App\Models\Merchant::payinStatus() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="payin_status" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="payin_status" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr><td>通道选择算法</td>
                                <td>
                                    @foreach(\App\Models\Merchant::payinIsCycle() as $k => $v)
                                        @if($k == 2)
                                            <input type="radio" name="payin_is_cycle" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="payin_is_cycle" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>代收通道组</td>
                                <td>
                                    <?php
                                    $listDS = \App\Models\ChannelOrderGroup::where('channel_type',1)->get();
                                    ?>
                                    <select name="in_sorting_channel_group">
                                        <option value="0">无</option>
                                        @foreach($listDS as $one)
                                        <option value="{{$one->id}}">{{$one->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>信用额度</td>
                                <td>
                                    <input type="text" name="credits_amount" value="0" autocomplete="off" class="form-control">
                                    <br>
                                    用于当商户代付余额不足时候平台可让你欠费，设计预留，目前未启用
                                </td>
                            </tr>
                            <tr><td>代收最低金额</td>
                                <td>
                                    <input type="text" name="in_min_amount" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>代收最高金额</td>
                                <td>
                                    <input type="text" name="in_max_amount" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>代付手续费</td>
                                <td>
                                    <input type="text" name="payout_poundage1" placeholder="百分之几" autocomplete="off">
                                    +
                                    <input type="text" name="payout_poundage2" placeholder="每笔" autocomplete="off">
                                </td>
                            </tr><tr><td>代付金额多少后不收每笔手续费</td>
                                <td>
                                    <input type="text" name="payout_trigger" value="0" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>每日代付限额</td>
                                <td>
                                    <input type="text" name="payout_limit" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>代付状态</td>
                                <td>
                                    @foreach(\App\Models\Merchant::payoutStatus() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="payout_status" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="payout_status" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr><tr><td>代付类型</td>
                                <td>
                                    @foreach(\App\Models\Merchant::payoutIsCycle() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="payout_is_cycle" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="payout_is_cycle" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr><td>代付固定通道</td>
                                <td>
                                    <select name="payout_channel_id"  class="form-control">
                                        <option value="0">无</option>
                                        @foreach($payoutchannelList as $value)
                                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>代付通道组</td>
                                <td>
                                    <?php
                                    $listDF = \App\Models\ChannelOrderGroup::where('channel_type',2)->get();
                                    ?>
                                    <select name="out_sorting_channel_group" class="form-control">
                                        <option value="0">无</option>
                                        @foreach($listDF as $one)
                                            <option value="{{$one->id}}">{{$one->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr><tr><td>代付最低金额</td>
                                <td>
                                    <input type="text" name="out_min_amount" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>代付最高金额</td>
                                <td>
                                    <input type="text" name="out_max_amount" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>是否允许手动代付</td>
                                <td>
                                    @foreach(\App\Models\Merchant::allowManualPayout() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="allow_manual_payout" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="allow_manual_payout" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr><td>报备IP</td>
                                <td>
                                    <input type="text" name="request_ip" placeholder="多个设置，用|隔开" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr><td>报备域名</td>
                                <td>
                                    <input type="text" name="request_domain" placeholder="多个设置，用|隔开" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr><td>类别</td>
                                <td>
                                    <select name="type"  class="form-control">
                                        @foreach(\App\Models\Merchant::type() as $k => $v)
                                            <option value="{{$k}}" >{{$v}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr><tr><td>商户官网</td>
                                <td>
                                    <input type="text" name="url" placeholder="" autocomplete="off" class="form-control">
                                </td>
                            </tr><tr><td>Telegram Id</td>
                                <td>
                                    <input type="text" name="tg_id" placeholder="" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr><td>谷歌校验码</td>
                                <td>
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a class="btn btn-success" onclick="commonPost('f')">保存</a>
                                    <button type="reset" class="btn btn-danger">重置</button>
                                </td>
                            </tr>
                                </tbody>
                            </table>
                        </form>

        </div>
    </div>
@endsection

@section('script')

@endsection
