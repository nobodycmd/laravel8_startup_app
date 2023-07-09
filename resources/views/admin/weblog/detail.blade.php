@extends('layouts.adminpop')

@section('layout')

    <div class="">请求信息</div>
    <div class="">
        <table class="table">
            <tbody>
            <tr>
                <td width="30%">模块</td>
                <td>{{$list['model']}}</td>
            </tr>
            <tr>
                <td width="30%">控制器</td>
                <td>{{$list['controll']}}</td>
            </tr>
            <tr>
                <td width="30%">方法</td>
                <td>{{$list['action']}}</td>
            </tr>
            <tr>
                <td width="30%">创建时间</td>
                <td>{{$list['create_time']}}</td>
            </tr>
            <tr>
                <td width="30%">操作用户</td>
                <td>{{$list['admin_user']}}</td>
            </tr>
            <tr>

                <td>请求参数</td>
                <td  style="word-break: break-word">{{$list["params"]}}</td>
            </tr>





            </tbody>
        </table>
    </div>

@endsection

@section('script')

@endsection
