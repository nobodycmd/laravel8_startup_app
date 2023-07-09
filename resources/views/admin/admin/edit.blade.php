@extends('layouts.adminpop')

@section('layout')
    
                        <form  style="margin-top: 15px;" name="f"  action="{{route('admin.admin.edit')}}">

                            <input type="hidden" name="id" value="{{$list['id']}}">
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                            <tr>
                                <td >角色名称</td>
                                <td >
                                    <select name="admin_role_id" class="form-control">
                                        <option value="">请选择</option>
                                        @foreach($adminAdminRoleIdList as $value)
                                            <option value="{{$value['id']}}" @if($value['id'] == $list['admin_role_id']) selected @endif>{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td >姓名</td>
                                <td >
                                    <input type="text" name="name" placeholder="请输入" autocomplete="off"  class="form-control" value="{{$list['name']}}">
                                </td>
                            </tr>
                            <tr>
                                <td >手机号</td>
                                <td >
                                    <input type="text" name="mobile" placeholder="请输入" autocomplete="off"  class="form-control" value="{{$list['mobile']}}">
                                </td>
                            </tr>
                            <tr>
                                <td >用户名</td>
                                <td >
                                    <input type="text" name="username" placeholder="请输入" autocomplete="off"  class="form-control" value="{{$list['username']}}">
                                </td>
                            </tr>
                            <tr>
                                <td >密码</td>
                                <td >
                                    <input type="password" name="password" placeholder="只能重置" autocomplete="off"  class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td >状态</td>
                                <td >
                                    @foreach(\App\Models\Admin::status() as $k => $v)
                                        @if($k == $list['status'])
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}" checked>
                                        @else
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}">
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td >谷歌校验码</td>
                                <td >
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-success" onclick="commonPost('f')" >保存</button>
                                    <button type="reset" class="btn btn-primary">重置</button>
                                </td>
                            </tr>
                                </tbody>
                            </table>
                        </form>
    
@endsection

@section('script')
@endsection
