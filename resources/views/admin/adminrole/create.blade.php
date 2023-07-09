@extends('layouts.adminpop')

@section('layout')
    
                        <form  style="margin-top: 15px;"  action="{{route('admin.adminrole.create')}}" name="f">
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                
                            <tr >
                                <td>名称</td>
                                <td>
                                    <input type="text" name="name" placeholder="请输入" autocomplete="off"  class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td>标识</td>
                                <td>
                                    <input type="text" name="identity" placeholder="请输入" autocomplete="off"  class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td>状态</td>
                                <td>
                                    @foreach(\App\Models\AdminRole::status() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}" checked>{{$v}}
                                        @else
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr >
                                <td>谷歌校验码</td>
                                <td>
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
@endsection
