@extends('layouts.merchant')

@section('layout')

    <div class="container-fluid" style="padding-top:15px;">



            <div class="alert alert-info" role="alert">
                余额：<span id="balance">{{$balance}}(含未下发{{$wait_settle}})</span> (账户冻结金额：<span id="freeze">{{$freeze}}</span>)
            </div>

            
            <div class="table-responsive">
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
            </div>

            <div class="paginationWrap">{{$paginator->links()}}</div>


    </div>
@endsection

@section('script')
    <script>
        layui.laydate.render({
            elem: '#settlement_time'
            ,type: 'date'
            ,range: '|'
        });
    </script>
@endsection
