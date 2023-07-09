@extends('layouts.adminpop')

@section('layout')
    
                        <form class="form" style="margin-top: 15px;" name="f" action="{{route('admin.agent.create')}}">
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                
                            <tr>
                                <td>上级代理</td>
                                <td>
                                    <select name="pid" class="form-control">
                                        <option value="0">顶级代理</option>
                                        @foreach($agentList as $value)
                                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>代理名称</td>
                                <td>
                                    <input type="text" name="name" placeholder="请输入" autocomplete="off" class="form-control" >
                                </td>
                            </tr>
                            <tr>
                                <td>备注信息</td>
                                <td>
                                    <input type="text" name="remark" placeholder="请输入" autocomplete="off" class="form-control" >
                                </td>
                            </tr>
                            <tr>
                                <td>代收手续费</td>
                                <td>
                                    <input type="text" name="payin_poundage1" placeholder="百分之几" autocomplete="off"  style="width: 100px;" class="form-control" >
                                    <span>+</span>
                                    <input type="text" name="payin_poundage2" placeholder="每笔" autocomplete="off"  style="width: 100px;" class="form-control" >
                                </td>
                            </tr>
                            <tr>
                                <td>代付手续费</td>
                                <td>
                                    <input type="text" name="payout_poundage1" placeholder="百分之几" autocomplete="off" style="width: 100px;" class="form-control">
                                    <span>+</span>
                                    <input type="text" name="payout_poundage2" placeholder="每笔" autocomplete="off" style="width: 100px;" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>状态</td>
                                <td>
                                    @foreach(\App\Models\Agent::status() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}" checked>
                                        @else
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}">
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>谷歌校验码</td>
                                <td>
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <a class="btn btn-success" onclick="commonPost('f')" >保存</a>
                                    <button type="reset" class="btn btn-primary">重置</button>
                                </td>
                            </tr>

                                </tbody>
                            </table>
                        </form>
          
    
@endsection

@section('script')
@endsection
