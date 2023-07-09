@extends('layouts.agent')

@section('layout')

                            <div class="container">
                                <table class="table">
                                    <colgroup>
                                        <col width="235">
                                        <col>
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                        <td>代理号</td>
                                        <td>{{$agentList['agentid']}}</td>
                                    </tr>
                                    <tr>
                                        <td>代理名称</td>
                                        <td>{{$agentList['name']}}</td>
                                    </tr>
                                    <tr>
                                        <td>代收手续费（百分之几+每笔）</td>
                                        <td>{{$agentList['payin_poundage']}}</td>
                                    </tr>
                                    <tr>
                                        <td>代付手续费（百分之几+每笔）</td>
                                        <td>{{$agentList['payout_poundage']}}</td>
                                    </tr>
                                    <tr>
                                        <td>状态</td>
                                        <td>{{$agentList['status']}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


@endsection

@section('script')
@endsection
