@extends('layouts.layout')

@section('layout')


    <form method="post" class="form-inline">
@csrf
        <div class="form-group">
            <select name="admin_role_id" id="admin_role_id" class="form-control">
                <option value="">角色名称</option>
                @foreach($adminAdminRoleIdList as $value)
                    <option value="{{$value['id']}}">{{$value['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="text" name="name" id="name" placeholder="姓名" autocomplete="off" class="form-control">
        </div>
        <div class="form-group">
            <select name="status" id="status" class="form-control">
                <option value="">状态</option>
                @foreach(\App\Models\Admin::status() as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <input type="submit">
        <a type="button"  class="btn btn-success"  onclick="commonOpen(this)" url="{{route('admin.admin.create')}}">添加</a>
    </form>

    <div class="table-responsive">

        <table class="table">
            <tbody>
            <tr>
                <th>id</th>
                <th>角色</th>
                <th>名字</th>
                <th>手机号</th>
                <th>账号</th>
                <th>状态</th>
                <th>谷歌</th>
            </tr>
            <?php
                /** @var \App\Models\Admin $one */
                ?>
            @foreach($list as $one)
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->role->name}}</td>
                    <td>{{$one->name}}</td>
                    <td>{{$one->mobile}}</td>
                    <td>{{$one->username}}</td>
                    <td>{{$one->status == 1 ? '正常' : '-'}}</td>
                    <td>{{$one->google_authenticator}}</td>
                    <td>
                        <a  class="btn btn-primary "  onclick="commonOpenForTr(this)" url="{{route('admin.admin.edit',['id'=>$one->id])}}" >编辑</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection


