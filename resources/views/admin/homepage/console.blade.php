@extends('layouts.layout')

@section('layout')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-6">

                <div class="panel panel-info">
                    <div class="panel-heading">今日数据</div>
                    <div class="panel-body">

                        <table class="table">
                            <colgroup>
                                <col width="180">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>member count</td>
                                <td>0</td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

            <div class="col-md-6">

                <div class="panel panel-info">
                    <div class="panel-heading">昨日数据</div>
                    <div class="panel-body">

                        <table class="table">
                            <colgroup>
                                <col width="180">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>member count</td>
                                <td>0</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <?php
                \App\Services\RedisService::getValue('TrafficCounter'.date('YmdHi'));
                ?>
                <div class="panel panel-info">
                    <div class="panel-heading">流量</div>
                    <div class="panel-body">

                        <table class="table">
                            <colgroup>
                                <col width="180">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>上分钟</td>
                                <td>{{  \App\Services\RedisService::getValue('TrafficCounter'.date('YmdHi',time()-60)) }}次请求</td>
                            </tr>
                            <tr>
                                <td>本分钟</td>
                                <td>{{  \App\Services\RedisService::getValue('TrafficCounter'.date('YmdHi')) }}次请求</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>


            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        setInterval(function(){
            location.reload();
        },80000);
    </script>
@endsection
