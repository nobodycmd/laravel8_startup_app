@extends('layouts.layout')
@section('layout')
    <div class="container-fluid" style="padding-top:15px;">
        <form method="post" class="form-inline">
        @csrf
        <!-- <div class="form-group">
    <label>id</label>
    <input type="text" class="form-control" name="id" id="id"  autocomplete="false" placeholder="id" value="{{request()->input('id','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>名称</label>
    <input type="text" class="form-control" name="name" id="name"  autocomplete="false" placeholder="名称" value="{{request()->input('name','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>标识</label>
    <input type="text" class="form-control" name="identity" id="identity"  autocomplete="false" placeholder="标识" value="{{request()->input('identity','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>状态（1正常，2禁用）</label>
    <input type="text" class="form-control" name="status" id="status"  autocomplete="false" placeholder="状态（1正常，2禁用）" value="{{request()->input('status','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>权限（多个设置，用|隔开）</label>
    <input type="text" class="form-control" name="permission" id="permission"  autocomplete="false" placeholder="权限（多个设置，用|隔开）" value="{{request()->input('permission','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>创建时间</label>
    <input type="text" class="form-control" name="create_time" id="create_time"  autocomplete="false" placeholder="创建时间" value="{{request()->input('create_time','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>更新时间</label>
    <input type="text" class="form-control" name="update_time" id="update_time"  autocomplete="false" placeholder="更新时间" value="{{request()->input('update_time','')}}">
</div> -->

            <input type="submit" class="btn btn-success"/>
            <a class="btn btn-success"  onclick="commonOpen(this)" url="{{route('admin.adminrole.create')}}">添加</a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>

                    <th>名称</th>

                    <th>标识</th>

                    <th>状态</th>

                    <th>操作</th>
                </tr>
                <?php/** @var App\Models\AdminRole[]  $list */ ?>
                <?php foreach($list as $one){ ?>
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->name}}</td>
                    <td>{{$one->identity}}</td>
                    <td>{{$one->status == 1 ? '正常' : '-'}}</td>
                    <td>
                        <a  class="btn btn-primary btn-sm"  onclick="commonOpenForTr(this)" url="{{route('admin.adminrole.edit',['id'=>$one->id])}}">编辑</a>

                        <a  class="btn btn-primary btn-sm"  onclick="commonOpenForTr(this)" url="{{route('admin.adminrole.permission',['id'=>$one->id])}}" >权限</a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        @endsection


        @section('script')
        @endsection