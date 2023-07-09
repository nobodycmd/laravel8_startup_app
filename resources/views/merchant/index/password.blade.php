@extends('layouts.merchant')

@section('layout')



            <form  action="{{route('merchant.index.password')}}" name="f">


                <table class="table">
                    <colgroup>
                        <col width="180">
                        <col>
                    </colgroup>
                    <tbody>

                    <tr>
                        <td>旧密码</td>
                        <td>
                            <input type="password" name="current_password" autocomplete="off" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>新密码</td>
                        <td>
                            <input type="password" name="password" autocomplete="off" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>重复新密码</td>
                        <td>
                            <input type="password" name="password_confirmation" autocomplete="off" class="form-control">
                        </td>
                    </tr>


                    <tr>
                        <td>谷歌校验码</td>
                        <td>
                            <input type="password" name="gc" autocomplete="off" class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <a class="btn btn-success" onclick="commonPost('f')">提交</a>
                        </td>
                    </tr>
                    </tbody>
                </table>


            </form>


@endsection

@section('script')
@endsection
