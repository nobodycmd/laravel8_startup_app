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
    <label>父级ID</label>
    <input type="text" class="form-control" name="pid" id="pid"  autocomplete="false" placeholder="父级ID" value="{{request()->input('pid','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>名称</label>
    <input type="text" class="form-control" name="name" id="name"  autocomplete="false" placeholder="名称" value="{{request()->input('name','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>URI（模块.控制器.操作）</label>
    <input type="text" class="form-control" name="uri" id="uri"  autocomplete="false" placeholder="URI（模块.控制器.操作）" value="{{request()->input('uri','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>排序值</label>
    <input type="text" class="form-control" name="sort" id="sort"  autocomplete="false" placeholder="排序值" value="{{request()->input('sort','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>状态（1正常，2禁用）</label>
    <input type="text" class="form-control" name="status" id="status"  autocomplete="false" placeholder="状态（1正常，2禁用）" value="{{request()->input('status','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>创建时间</label>
    <input type="text" class="form-control" name="create_time" id="create_time"  autocomplete="false" placeholder="创建时间" value="{{request()->input('create_time','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>更新时间</label>
    <input type="text" class="form-control" name="update_time" id="update_time"  autocomplete="false" placeholder="更新时间" value="{{request()->input('update_time','')}}">
</div> -->

            <a class="btn btn-success" onclick="commonOpen(this)" url="{{route('admin.adminpermission.create')}}">添加</a>
{{--            <input type="submit" class="btn btn-success"/>--}}
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>
                    <th>父级ID</th>
                    <th>名称</th>
                    <th>Route Name</th>
                    <th>排序值</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php foreach($list as $one){ ?>
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->pid}}</td>
                    <td>{!! $one->name !!}</td>
                    <td>{{$one->uri}}</td>
                    <td>{{$one->sort}}</td>
                    <td>{{$one->status == 1 ? '正常' : '禁用'}}</td>
                    <td>
                        <a   class="btn btn-success"   onclick="commonOpenForTr(this)" url="{{route('admin.adminpermission.edit',['id'=>$one->id])}}">编辑</a>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        @endsection


        @section('script')
        @endsection

