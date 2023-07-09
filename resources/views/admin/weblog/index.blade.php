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
    <label>模块</label>
    <input type="text" class="form-control" name="model" id="model"  autocomplete="false" placeholder="模块" value="{{request()->input('model','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>控制器</label>
    <input type="text" class="form-control" name="controll" id="controll"  autocomplete="false" placeholder="控制器" value="{{request()->input('controll','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>方法</label>
    <input type="text" class="form-control" name="action" id="action"  autocomplete="false" placeholder="方法" value="{{request()->input('action','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>请求方式</label>
    <input type="text" class="form-control" name="request_type" id="request_type"  autocomplete="false" placeholder="请求方式" value="{{request()->input('request_type','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>参数</label>
    <input type="text" class="form-control" name="params" id="params"  autocomplete="false" placeholder="参数" value="{{request()->input('params','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>操作用户ID</label>
    <input type="text" class="form-control" name="admin_user_id" id="admin_user_id"  autocomplete="false" placeholder="操作用户ID" value="{{request()->input('admin_user_id','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>操作时间</label>
    <input type="text" class="form-control" name="create_time" id="create_time"  autocomplete="false" placeholder="操作时间" value="{{request()->input('create_time','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>request_ip</label>
    <input type="text" class="form-control" name="request_ip" id="request_ip"  autocomplete="false" placeholder="request_ip" value="{{request()->input('request_ip','')}}">
</div> -->


            <input type="submit" class="btn btn-success"/>
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>

                    <th>模块</th>

                    <th>控制器</th>

                    <th>方法</th>

                    <th>请求方式</th>

                    <th>参数</th>

                    <th>操作用户ID</th>

                    <th>操作时间</th>

                    <th>request_ip</th>

                    <th>操作</th>
                </tr>

                <?php foreach($list as $one){ ?>
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->model}}</td>
                    <td>{{$one->controll}}</td>
                    <td>{{$one->action}}</td>
                    <td>{{$one->request_type}}</td>
                    <td>{{$one->params}}</td>
                    <td>{{$one->admin_user_id}}</td>
                    <td>{{$one->create_time}}</td>
                    <td>{{$one->request_ip}}</td>
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