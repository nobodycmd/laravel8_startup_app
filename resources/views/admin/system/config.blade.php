@extends('layouts.layout')

@section('layout')
    
                        <form   style="margin-top: 15px;" action="{{route('admin.system.config')}}" name="f">

                            <input type="hidden" value="{{$info['id']}}" name="id" />
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>


                                <tr >
                                <td >代收频率</td>
                                <td >
                                    <input type="text" name="payin_frequency" placeholder="间隔几秒/代收每次" autocomplete="off" class="form-control" value="{{$info['payin_frequency']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >代收查询频率</td>
                                <td >
                                    <input type="text" name="payinquery_frequency" placeholder="间隔几秒/代收每次" autocomplete="off" class="form-control" value="{{$info['payinquery_frequency']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >代付频率</td>
                                <td >
                                    <input type="text" name="payout_frequency" placeholder="间隔几秒/代付每次" autocomplete="off" class="form-control" value="{{$info['payout_frequency']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >代付查询频率</td>
                                <td >
                                    <input type="text" name="payoutquery_frequency" placeholder="间隔几秒/代付每次" autocomplete="off" class="form-control" value="{{$info['payoutquery_frequency']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >代付余额查询频率</td>
                                <td >
                                    <input type="text" name="payoutbalancequery_frequency" placeholder="间隔几秒/代付每次" autocomplete="off" class="form-control" value="{{$info['payoutbalancequery_frequency']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >代收是否结算</td>
                                <td >
                                    <input type="radio" name="is_settlement" value="2" title="是" @if($info['is_settlement'] == 2) checked @endif>是
                                    <input type="radio" name="is_settlement" value="3" title="否" @if($info['is_settlement'] == 3) checked @endif>否
                                </td>
                            </tr>
                            <tr >
                                <td >D0代收是否结算</td>
                                <td >
                                    <input type="radio" name="D0_is_settlement" value="2" title="是" @if($info['D0_is_settlement'] == 2) checked @endif>是
                                    <input type="radio" name="D0_is_settlement" value="3" title="否" @if($info['D0_is_settlement'] == 3) checked @endif>否
                                </td>
                            </tr>
                            <tr >
                                <td >结算截止日期</td>
                                <td >
                                    <input type="text" name="settlement_time" class="form-control" id="settlement_time" value="{{$info['settlement_time']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >不服务地区是否开启</td>
                                <td >
                                    <input type="radio" name="unservedregion_isopen" value="1" title="是" @if($info['unservedregion_isopen'] == 1) checked @endif>是
                                    <input type="radio" name="unservedregion_isopen" value="0" title="否" @if($info['unservedregion_isopen'] == 0) checked @endif>否
                                </td>
                            </tr>
                            <tr >
                                <td >开启数据包统计</td>
                                <td >
                                    <input type="radio" name="packet_isopen" value="1" title="是" @if($info['packet_isopen'] == 1) checked @endif>是
                                    <input type="radio" name="packet_isopen" value="0" title="否" @if($info['packet_isopen'] == 0) checked @endif>否
                                </td>
                            </tr>
                            <tr >
                                <td >数据包抓取时间</td>
                                <td >
                                    <input type="text" name="packet_time" placeholder="分钟,-1永久开启" autocomplete="off" class="form-control" value="{{$info['packet_time']}}">
                                </td>
                            </tr>
                            <tr >
                                <td >不服务地区默认支付通道</td>
                                <td >
                                    <select name="unservedregion_payinchannel" class="form-control">
                                        <option value="0">请选择</option>
                                        @foreach($payinchannel_list as $key => $value)
                                            <option value="{{$value['id']}}" @if($value['id'] == $info['unservedregion_payinchannel']) selected @endif>{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr >
                                <td >谷歌校验码</td>
                                <td >
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td colspan="2">
                                    <a class="btn btn-success" onclick="commonPost('f')">保存</a>
                                    <button type="reset" class="btn btn-primary">重置</button>
                                </td>
                            </tr>
                                </tbody>
                            </table>
                        </form>
          
    
@endsection

@section('script')
    <script>
        ;!function(){
            var $ = layui.$
                    ,table = layui.table
                    ,laydate = layui.laydate;
            laydate.render({
                elem: '#settlement_time'
                ,type: 'date'
            });
        }();
    </script>
@endsection
