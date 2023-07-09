@extends('layouts.layout')

@section('layout')


    <div class="panel panel-info">
        <div class="panel-heading">历史记录</div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <td>类型</td>
                    <td>之前</td>
                    <td>涉及金额</td>
                    <td>之后</td>
                    <td>备注</td>
                    <td>时间</td>
                </tr>
                @foreach($list as $one)
                    <tr>
                        <td>{{$one['type_name']}}</td>
                        <td>{{$one['before_balance']}}</td>
                        <td>{{$one['amount']}}</td>
                        <td>{{$one['after_balance']}}</td>
                        <td>{{$one['remark']}}</td>
                        <td>{{$one['create_time']}}</td>
                    </tr>
                @endforeach
            </table>

            <div class="paginationWrap">{{$paginator->links()}}</div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        ;!function(){
            var $ = layui.$
                ,table = layui.table
                ,laydate = layui.laydate;

            $('#inner-search').on('click', function(){

            });

            laydate.render({
                elem: '#settlement_time'
                ,type: 'date'
                ,range: '|'
            });
        }();
    </script>
@endsection
