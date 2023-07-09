@extends('layouts.adminpop')

@section('layout')


                    <div class="container-fluid">
                        <form  action="{{route('admin.homepage.ordernumbercount')}}" method="get"  class="form-inline">
@csrf
                                <div class="form-group">

                                    <label>时间间隔</label>

                                    <select name="type" id="type" class="form-control" >
                                        <option value="">时间间隔</option>
                                        <option value="1" selected>按每5分钟统计</option>
                                        <option value="3">按小时统计</option>
                                        <option value="4">按天统计</option>
                                    </select>

                                </div>


                                <div class="form-group">
                                    <label>指定日期</label>
                                    <input type="text" name="create_time" id="create_time" placeholder="" autocomplete="off" class="form-control">
                                </div>


                                <input type="submit" class="btn btn-success"/>

                            <span class="bg-info" id="djs"></span>

                        </form>

                        <div id="payin" style="width: 100%;height: 250px;"></div>

                        <div id="payout" style="width:100%;height: 250px;"></div>


                    </div>


@endsection
@section('script')




    <script>

        var t = 300;
        setInterval(function () {
            t -=1;
            $('#djs').html(t+'秒后刷新');
            if(t == 0){
                location = location;
            }
        },1000)






        echarts.init(document.getElementById('payin')).setOption({
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                type: 'scroll',
                data: <?=json_encode($return['payin']['legend_data'],JSON_UNESCAPED_UNICODE) ?>
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: <?=json_encode($return['payin']['xaxis_data']) ?>
            },
            yAxis: {
                type: 'value'
            },
            series: <?=json_encode($return['payin']['series']) ?>
        })

        echarts.init(document.getElementById('payout')).setOption({
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                type: 'scroll',
                data: <?=json_encode($return['payout']['legend_data'],JSON_UNESCAPED_UNICODE)?>
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: <?=json_encode($return['payout']['xaxis_data'])?>
            },
            yAxis: {
                type: 'value'
            },
            series: <?=json_encode($return['payout']['series'])?>
        })






        laydate = layui.laydate
            laydate.render({
                elem: '#create_time'
                ,type: 'date'
                ,range: '|',
                trigger: 'click',
                value:getDay(),
                ready:function (){
                    document.activeElement.blur()
                },

            })





        function getDay(){
            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth()+1;
            var date = today.getDate();
            return year+"-"+month+"-"+date+" | "+ year+"-"+month+"-"+date;
        }


    </script>
@endsection
