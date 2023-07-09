@extends('layouts.adminpop')

@section('layout')
    
                        <form   action="{{route('admin.adminpermission.edit')}}" name="f">

                            <input type="hidden" name="id" value="{{$list['id']}}">
                            
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>

                                <tr>
                                <td>父级权限</td>
                                <td>
                                    <select name="pid" class="form-control">
                                        <option value="0">顶级权限</option>
                                        @foreach($adminPermissionPidList as $value)
                                            <option value="{{$value['id']}}" @if($value['id'] == $list['pid']) selected @endif>{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>名称</td>
                                <td>
                                    <input type="text" name="name" placeholder="请输入" autocomplete="off" class="form-control" value="{{$list['name']}}">
                                </td>
                            </tr>
                            <tr>
                                <td>URI</td>
                                <td>
                                    <input type="text" name="uri" placeholder="" autocomplete="off" class="form-control" value="{{$list['uri']}}">
                                </td>
                            </tr>
                            <tr>
                                <td>排序值</td>
                                <td>
                                    <input type="text" name="sort" placeholder="请输入" autocomplete="off" class="form-control" value="{{$list['sort']}}">
                                </td>
                            </tr>
                            <tr>
                                <td>状态</td>
                                <td>
                                    @foreach(\App\Models\AdminPermission::status() as $k => $v)
                                        @if($k == $list['status'])
                                            <input type="radio" name="status" value="{{$k}}" title="" checked>{{$v}}
                                        @else
                                            <input type="radio" name="status" value="{{$k}}" title="{{$v}}">{{$v}}
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
