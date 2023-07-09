@extends('layouts.agent')

@section('layout')
    <div class="container-fluid">
    商户最近10天代收成功金额折线图

    <div id="echart" style="width: 100%;height: 430px;"></div>

    </div>
@endsection

@section('script')
    <script>
                echarts.init(document.getElementById('echart')).setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        type: 'scroll',
                        data: <?= json_encode($graphData['legend_data']) ?>,
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
                        data: <?= json_encode($graphData['xaxis_data']) ?>,
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: <?= json_encode($graphData['series']) ?>,
                });
    </script>
@endsection
