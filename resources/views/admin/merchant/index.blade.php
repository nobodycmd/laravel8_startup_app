
@extends('layouts.layout')
@section('layout')
    <div class="container-fluid" style="padding-top:15px;">
        <form method="post" class="form-inline">
            @csrf
            <div class="form-group">
                <label>id</label>
                <input type="text" class="form-control" name="id" id="id"  autocomplete="false" placeholder="id" value="{{request()->input('id','')}}">
            </div>
            <div class="form-group">
                <label>商户号</label>
                <input type="text" class="form-control" name="merchantid" id="merchantid"  autocomplete="false" placeholder="商户号" value="{{request()->input('merchantid','')}}">
            </div>
            <div class="form-group">
                <label>商户号</label>
                <select name="type"  class="form-control"  >
                    <option value="">类型</option>
                    @foreach(\App\Models\Merchant::type() as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <input type="submit" class="btn btn-success"/>

            <a  class="btn btn-success"   onclick="commonOpen(this)" url="{{route('admin.merchant.create')}}" >新增</a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>

                    <th>商户号</th>

                    <th>余额</th>

                    <th>代理ID</th>

                    <th>商户名称</th>

                    <th>统一引用</th>

                    <th>结算周期</th>

                    <th>状态</th>

{{--                    <th>手动代收手续费</th>--}}

                    <th>代收手续费</th>

                    <th>代收限额</th>

                    <th>代收状态</th>

                    <th>代收是否轮训</th>

                    <th>代收通道ID</th>

                    <th>代付手续费%+N</th>

{{--                    <th>代付：代付金额多少后不收每笔手续费</th>--}}

                    <th>代付限额</th>

                    <th>代付状态</th>

                    <th>代付类型</th>

                    <th>代付通道ID</th>

                    <th>是否删除</th>

                    <th>报备IP</th>

                    <th>报备域名</th>

                    <th>创建时间</th>

                    <th>更新时间</th>

                    <th>商户跳转链接</th>

                    <th>代收结算比例%</th>

                    <th>是否结算</th>

                    <th>商户官网</th>

                    <th>代收组</th>

                    <th>代付组</th>

                    <th>信用额度</th>

                    <th>用户冻结金额</th>

                    <th>代收最低金额</th>

                    <th>代收最高金额</th>

                    <th>代付最低金额</th>

                    <th>代付最高金额</th>

                    <th>代理的代收费率</th>

                    <th>代理的代付费率</th>

                    <th>允许手动代付</th>

                    <th>产品名称</th>

                    <th>产品logo</th>

                    <th>tg群ID</th>

                    <th>操作</th>
                </tr>
                <?php
                /** @var \App\Models\Merchant $one */
                foreach($list as $one){ ?>
                <tr>
                    <td>
                        {{$one['id']}}
                    </td>
                    <td>
                        {{$one->merchantid}}
                    </td>
                    <td>
                        {{$one->balance}}
                    </td>
                    <td>
                        {{$one->agent_id}}
                    </td>
                    <td>
                        {{$one->name}}
                    </td>
                    <td>
                        {{$one->identity}}
                    </td>
                    <td>
                        {{$one->settlement_cycle}}
                    </td>
                    <td>
                        {{$one->status ==1 ? '正常' : '-'}}
                    </td>
{{--                    <td>--}}
{{--                        {{$one['manual_payin_poundage']}}--}}
{{--                    </td>--}}
                    <td>
                        {{$one->payin_poundage}}
                    </td>
                    <td>
                        {{$one->payin_limit}}
                    </td>
                    <td>
                        {{$one->payin_status ==1 ? '正常' : '-'}}
                    </td>
                    <td>
                        {{$one->payin_is_cycle == 1 ? '是' : '否'}}
                    </td>
                    <td>
                        {{$one->payin_channel_id}}
                    </td>
                    <td>
                        {{$one->payout_poundage}}
                    </td>
{{--                    <td>--}}
{{--                        {{$one->payout_trigger}}--}}
{{--                    </td>--}}
                    <td>
                        {{$one->payout_limit}}
                    </td>
                    <td>
                        {{$one->payout_status == 1?'正常':'-'}}
                    </td>
                    <td>
                        {{$one::payoutIsCycle( $one->payout_is_cycle )}}
                    </td>
                    <td>
                        {{$one->payout_channel_id}}
                    </td>
                    <td>
                        {{$one::isDelete($one->is_delete)}}
                    </td>
                    <td>
                        {{$one->request_ip}}
                    </td>
                    <td>
                        {{$one->request_domain}}
                    </td>
                    <td>
                        {{$one->create_time}}
                    </td>
                    <td>
                        {{$one->update_time}}
                    </td>
                    <td>
                        {{$one->jump_link}}
                    </td>
                    <td>
                        {{$one->payinsettlement_proportion}}
                    </td>

                    <td>
                        {{$one->is_settlement == 1 ? '是':'否'}}
                    </td>
                    <td>
                        {{$one->url}}
                    </td>

                    <td>
                        {{$one->in_sorting_channel_group}}
                    </td>
                    <td>
                        {{$one->out_sorting_channel_group}}
                    </td>
                    <td>
                        {{$one->credits_amount}}
                    </td>
                    <td>
                        {{$one->freeze_amount}}
                    </td>
                    <td>
                        {{$one->in_min_amount}}
                    </td>
                    <td>
                        {{$one->in_max_amount}}
                    </td>
                    <td>
                        {{$one->out_min_amount}}
                    </td>
                    <td>
                        {{$one->out_max_amount}}
                    </td>
                    <td>
                        {{$one->agent_in_fee}}
                    </td>
                    <td>
                        {{$one->agent_out_fee}}
                    </td>
                    <td>
                        {{$one->allow_manual_payout}}
                    </td>
                    <td>
                        {{$one->product_name}}
                    </td>
                    <td>
                        {{$one->product_logo}}
                    </td>
                    <td>
                        {{$one->tg_id}}
                    </td>

                    <td>
                        <a style="font-size: 11px;padding: 0;" class="btn btn-primary btn-sm"  onclick="commonOpenForTr(this)" url="{{route('admin.merchant.edit',['id'=>$one['id']])}}" >编辑</a>

                        <a style="font-size: 11px;padding: 0;" class="btn btn-primary btn-sm"   onclick="commonOpenForTr(this)" url="{{route('admin.merchant.balance',['id'=>$one['id']])}}" >余额管理</a>

                        <a style="font-size: 11px;padding: 0;"  class="btn btn-primary btn-sm"   onclick="postDirectlyWithGoogleCode(this)" url="{{route('admin.merchant.password',['id'=>$one['id']])}}">重置密码</a>

                        <a style="font-size: 11px;padding: 0;"  class="btn btn-primary btn-sm"  onclick="postDirectlyWithGoogleCode(this)" url="{{route('admin.merchant.secretkey',['id'=>$one['id']])}}">重置密钥</a>

                        <a style="font-size: 11px;padding: 0;"   class="btn btn-primary btn-sm"  onclick="postDirectlyWithGoogleCode(this)" url="{{route('admin.merchant.googleauthenticator',['id'=>$one['id']])}}">谷歌code</a>

                        <a style="font-size: 11px;padding: 0;"   class="btn btn-primary btn-sm"  onclick="postDirectlyWithGoogleCode(this,function (res){location=res.data.quickloginurl})" url="{{route('admin.merchant.quicklogin',['id'=>$one['id']])}}" target="_blank" >快速登录</a>

                        <a  style="font-size: 11px;padding: 0;"  class="btn btn-primary btn-sm"  href="{{route('lightpays.test.payment')}}/{{ $one['merchantid'] }}" target="_blank">测试代收</a>

                        <a  style="font-size: 11px;padding: 0;"  class="btn btn-primary btn-sm"  href="{{route('lightpays.test.transfer')}}/{{ $one['merchantid'] }}" target="_blank">测试代付</a>

                        <a style="font-size: 11px;padding: 0;"   class="btn btn-primary btn-sm"  onclick="postDirectlyWithGoogleCode(this,function (res){alert(res.data.n + '个代付订单被取消')})" url="{{route('admin.merchant.cancelPayoutFileOrders',['id'=>$one['id']])}}">取消代付文件订单</a>
                    </td>

                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        {{$paginator->links()}}
        @endsection


        @section('script')
        @endsection


