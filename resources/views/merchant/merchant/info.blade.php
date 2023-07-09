@extends('layouts.merchant')

@section('layout')

    <?php
    /** @var $merchantList \App\Models\Merchant  */
    ?>

            <table class="table">
                <colgroup>
                    <col width="180">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <td>国家</td>
                    <td>{{$country}}</td>
                </tr>
                <tr>
                    <td>商户ID</td>
                    <td>{{$merchantList->merchantid}}</td>
                </tr>
                <tr>
                    <td>商户名称</td>
                    <td>{{$merchantList->name}}</td>
                </tr>
                <tr>
                    <td>统一引用</td>
                    <td>
                        {{$merchantList->identity}}
                        <a class="btn btn-success" url="{{route('merchant.merchant.innertransfer')}}" onclick="commonOpen(this)">同引用下商户转账</a>
                    </td>
                </tr>
                <tr>
                    <td>商户余额</td>
                    <td>{{$merchantList->balance}}</td>
                </tr>
                <tr>
                    <td>待下发</td>
                    <td>{{  \App\Services\AccountService::getWaitSettle($merchantList->merchantid) }}</td>
                </tr>
                <tr>
                    <td>结算周期</td>
                    <td>{{$merchantList->settlement_cycle}}</td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td>{{$merchantList->status}}</td>
                </tr>
                <tr>
                    <td>代收手续费（%+N）</td>
                    <td>{{$merchantList->payin_poundage}}</td>
                </tr>
                <tr>
                    <td>今日代收限额</td>
                    <td>{{$merchantList->payin_limit}}</td>
                </tr>
                <tr>
                    <td>代收状态</td>
                    <td>{{$merchantList->payin_status == 1 ? '正常':'-'}}</td>
                </tr>
                <tr>
                    <td>代付手续费（%+N）</td>
                    <td>{{$merchantList->payout_poundage}}</td>
                </tr>
                <tr>
                    <td>今日代付限额</td>
                    <td>{{$merchantList->payout_limit}}</td>
                </tr>
                <tr>
                    <td>代付状态</td>
                    <td>{{$merchantList->payout_status == 1 ? '正常':'-'}}</td>
                </tr>
                <tr>
                    <td>密钥</td>
                    <td>
                        <a class="btn btn-success"  onclick="seekey()" >查看密钥</a>
                        <a class="btn btn-success"  onclick="resetkey()">重置密钥</a>
                    </td>
                </tr>
                <tr>
                    <td>谷歌身份验证器</td>
                    <td>
                        <a class="btn btn-success" onclick="seegc()">查看</a>
                        <a class="btn btn-success" onclick="resetgc()">重置</a>
                    </td>
                </tr>
                <tr>
                    <td>测试效果页面</td>
                    <td>
                        <a href="{{route('lightpays.test.payment',['merchant_id' => $merchantList->id ])}}" target="_blank">代收</a>

                        <a href="{{route('lightpays.test.transfer',['merchant_id' => $merchantList->id ])}}" target="_blank">代付</a>
                    </td>
                </tr>
                <tr>
                    <td>代码DEMO</td>
                    <td><a target="_blank" href="{{route('merchant.merchant.code')}}">查看代码</a></td>
                </tr>
                </tbody>
            </table>

@endsection

@section('script')
    <script>
        function resetgc(){
            postDirectlyWithGoogleCode("{{route('merchant.merchant.resetgc')}}",function (res) {
                alert(res.message)
            })
        }

        function seegc(){
            postDirectlyWithGoogleCode("{{route('merchant.merchant.seegc')}}",function (res) {
                alert(res.message)
            })
        }

        function seekey(){
            postDirectlyWithGoogleCode("{{route('merchant.merchant.seekey')}}",function (res) {
                alert(res.message)
            })
        }

        function resetkey(){
            postDirectlyWithGoogleCode("{{route('merchant.merchant.resetkey')}}",function (res) {
                alert(res.message)
            })
        }


    </script>
@endsection
