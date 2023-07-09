@extends('layouts.adminpop')

@section('layout')
    
                        <form  style="margin-top: 15px;" name="f" action="{{route('admin.admin.create')}}">

                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                            
                            <tr >
                                <td >角色名称</td>
                                <td >
                                    <select name="admin_role_id" lay-search>
                                        <option value="">请选择</option>
                                        @foreach($adminAdminRoleIdList as $value)
                                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr >
                                <td >姓名</td>
                                <td >
                                    <input type="text" name="name" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td >手机号</td>
                                <td >
                                    <input type="text" name="mobile" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td >用户名</td>
                                <td >
                                    <input type="text" name="username" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td >密码</td>
                                <td >
                                    <input type="password" name="password" placeholder="密码长度最小为6个字符" minlength="6" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td >状态</td>
                                <td >
                                    @foreach(\App\Models\Admin::status() as $k => $v)
                                        @if($k == 1)
                                            <input type="radio" name="status" value="{{$k}}" title="" checked>{{$v}}
                                        @else
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}">{{$v}}
                                        @endif
                                    @endforeach
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
@endsection
