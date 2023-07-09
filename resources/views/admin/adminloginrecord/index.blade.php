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
    <label>管理员ID</label>
    <input type="text" class="form-control" name="admin_id" id="admin_id"  autocomplete="false" placeholder="管理员ID" value="{{request()->input('admin_id','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>登录IP</label>
    <input type="text" class="form-control" name="login_ip" id="login_ip"  autocomplete="false" placeholder="登录IP" value="{{request()->input('login_ip','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>登录时间</label>
    <input type="text" class="form-control" name="login_time" id="login_time"  autocomplete="false" placeholder="登录时间" value="{{request()->input('login_time','')}}">
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
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>

                    <th>管理员ID</th>

                    <th>登录IP</th>

                    <th>登录时间</th>

                    <th>创建时间</th>

                    <th>更新时间</th>

                    <th>操作</th>
                </tr>

                <?php foreach($list as $one){ ?>
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->admin_id}}</td>
                    <td>{{$one->login_ip}}</td>
                    <td>{{$one->login_time}}</td>
                    <td>{{$one->create_time}}</td>
                    <td>{{$one->update_time}}</td>
                    <td>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    {{$paginator->links()}}
@endsection


@section('script')
@endsection