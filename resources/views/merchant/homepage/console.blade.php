@extends('layouts.merchant')

@section('layout')

    <div class="panel panel-info">
        <div class="panel-heading">控制台</div>
        <div class="panel-body">

            <div class="alert alert-danger" role="alert">
                运营服务时间12:30-次日3:00联系方式:@hiyou2023
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">代收金额</div>
                        <div class="panel-body">


                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>今日总金额</td>
                                    <td>{{$list['payin_total_fee']}}</td>
                                </tr>
                                <tr>
                                    <td>今日成功金额</td>
                                    <td>{{$list['payin_total_fee_payin_success']}}</td>
                                </tr>
                                <tr>
                                    <td>昨日成功总金额</td>
                                    <td>{{$list['yesterday_payin_total_fee_success']}}</td>
                                </tr>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">代收数量</div>
                        <div class="panel-body">


                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>今日代收总订单数</td>
                                    <td>{{$list['payin_count']}}</td>
                                </tr>
                                <tr>
                                    <td>今日成功订单数</td>
                                    <td>{{$list['payin_count_payin_success']}}</td>
                                </tr>
                                <tr>
                                    <td>昨日成功代收总订单数</td>
                                    <td>{{$list['yesterday_payin_count_success']}}</td>
                                </tr>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">代付金额</div>
                        <div class="panel-body">


                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>今日总金额</td>
                                    <td>{{$list['payout_total_fee']}}</td>
                                </tr>
                                <tr>
                                    <td>今日成功金额</td>
                                    <td>{{$list['payout_total_fee_payout_success']}}</td>
                                </tr>
                                <tr>
                                    <td>昨日成功总金额</td>
                                    <td>{{$list['yesterday_payout_total_fee_success']}}</td>
                                </tr>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">代付数量</div>
                        <div class="panel-body">


                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>今日总订单数</td>
                                    <td>{{$list['payout_count']}}</td>
                                </tr>
                                <tr>
                                    <td>今日成功订单数</td>
                                    <td>{{$list['payout_count_payout_success']}}</td>
                                </tr>
                                <tr>
                                    <td>昨日成功订单数</td>
                                    <td>{{$list['yesterday_payout_count_success']}}</td>
                                </tr>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
@endsection
