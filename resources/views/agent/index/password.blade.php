@extends('layouts.agent')

@section('layout')
    
                        <form method="post" action="{{route('agent.index.password')}}" name="f">
                            @csrf
                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>
                                
                            <tr >
                                <td >当前密码</td>
                                <td >
                                    <input type="password" name="current_password" placeholder="" autocomplete="off" class="form-control" >
                                </td>
                            </tr>
                            <tr >
                                <td >新密码</td>
                                <td >
                                    <input type="password" name="password" placeholder="" minlength="6" autocomplete="off"  class="form-control" >
                                </td>
                            </tr>
                            <tr >
                                <td >确认新密码</td>
                                <td >
                                    <input type="password" name="password_confirmation" placeholder="" autocomplete="off"  class="form-control" >
                                </td>
                            </tr>
                            <tr >
                                <td >谷歌校验码</td>
                                <td >
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off"  class="form-control" >
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
