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
    <label>代理号</label>
    <input type="text" class="form-control" name="agentid" id="agentid"  autocomplete="false" placeholder="代理号" value="{{request()->input('agentid','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>父级ID</label>
    <input type="text" class="form-control" name="pid" id="pid"  autocomplete="false" placeholder="父级ID" value="{{request()->input('pid','')}}">
</div> -->

         <div class="form-group">
    <label>代理名称</label>
    <input type="text" class="form-control" name="name" id="name"  autocomplete="false" placeholder="代理名称" value="{{request()->input('name','')}}">
</div>

        <!-- <div class="form-group">
    <label>代收手续费（百分之几+每笔）</label>
    <input type="text" class="form-control" name="payin_poundage" id="payin_poundage"  autocomplete="false" placeholder="代收手续费（百分之几+每笔）" value="{{request()->input('payin_poundage','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>代付手续费（百分之几+每笔）</label>
    <input type="text" class="form-control" name="payout_poundage" id="payout_poundage"  autocomplete="false" placeholder="代付手续费（百分之几+每笔）" value="{{request()->input('payout_poundage','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>状态（1正常，2禁用）</label>
    <input type="text" class="form-control" name="status" id="status"  autocomplete="false" placeholder="状态（1正常，2禁用）" value="{{request()->input('status','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>密码</label>
    <input type="text" class="form-control" name="password" id="password"  autocomplete="false" placeholder="密码" value="{{request()->input('password','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>remember_token</label>
    <input type="text" class="form-control" name="remember_token" id="remember_token"  autocomplete="false" placeholder="remember_token" value="{{request()->input('remember_token','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>Google身份验证器</label>
    <input type="text" class="form-control" name="google_authenticator" id="google_authenticator"  autocomplete="false" placeholder="Google身份验证器" value="{{request()->input('google_authenticator','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>创建时间</label>
    <input type="text" class="form-control" name="create_time" id="create_time"  autocomplete="false" placeholder="创建时间" value="{{request()->input('create_time','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>更新时间</label>
    <input type="text" class="form-control" name="update_time" id="update_time"  autocomplete="false" placeholder="更新时间" value="{{request()->input('update_time','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>备注</label>
    <input type="text" class="form-control" name="remark" id="remark"  autocomplete="false" placeholder="备注" value="{{request()->input('remark','')}}">
</div> -->


            <input type="submit" class="btn btn-success"/>


            <a   class="btn btn-success" onclick="commonOpen(this)" url="{{route('admin.agent.create')}}">添加</a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>

                    <th>代理号</th>

                    <th>父级ID</th>

                    <th>代理名称</th>

                    <th>代收手续费（%+N）</th>

                    <th>代付手续费（%+N）</th>

                    <th>状态</th>

                    <th>Google身份验证器</th>

                    <th>创建时间</th>

                    <th>更新时间</th>

                    <th>备注</th>

                    <th>操作</th>
                </tr>

                <?php
                /** @var \App\Models\Agent $one */
                foreach($list as $one){ ?>
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->agentid}}</td>
                    <td>{{$one->pid}}</td>
                    <td>{{$one->name}}</td>
                    <td>{{$one->payin_poundage}}</td>
                    <td>{{$one->payout_poundage}}</td>
                    <td>{{$one->status == 1 ? '正常':'-'}}</td>
                    <td>{{$one->google_authenticator}}</td>
                    <td>{{$one->create_time}}</td>
                    <td>{{$one->update_time}}</td>
                    <td>{{$one->remark}}</td>
                    <td>
                        <a  class="btn btn-primary" onclick="commonOpenForTr(this)"  url="{{route('admin.agent.edit',['id'=>$one->id])}}" >编辑</a>

                        <a  class="btn btn-primary"   onclick="postDirectlyWithGoogleCode(this)" url="{{route('admin.agent.password',['id'=>$one->id])}}" >重置密码</a>

{{--                        <a  class="btn btn-primary"   onclick="commonOpenForTr(this)" url="{{route('admin.agent.googleauthenticator',['id'=>$one->id])}}" >谷歌身份验证器</a>--}}

                        <a  class="btn btn-primary"   onclick="postDirectlyWithGoogleCode(this,function(res) {location = res.data.quickloginurl})" url="{{route('admin.agent.quicklogin',['id'=>$one->id])}}" >快速登录</a>
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


