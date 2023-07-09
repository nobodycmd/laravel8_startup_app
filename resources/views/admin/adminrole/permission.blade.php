
@extends('layouts.layout')
@section('layout')
    <div class="container-fluid" style="padding-top:15px;">

        <a class="btn btn-success" onclick="save()">保存权限设置</a>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>
                        <input type="checkbox" onclick="selectAll('id')">
                    </th>

                    <th>名称</th>

                    <th>uri</th>

                    <th>sort</th>
                </tr>
                <?php
                /** @var \App\Models\AdminPermission $one */
                foreach($list as $one){ ?>
                <tr>
                    <td>
                        <input type="checkbox" name="id" @if($one->LAY_CHECKED) checked @endif value="{{$one->id}}" />
                    </td>
                    <td>{!! $one->name !!}</td>
                    <td>{{$one->uri}}</td>
                    <td>{{$one->sort}}</td>
                    <td>{{$one->status}}</td>

                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        @endsection


        @section('script')
            <script>
    function save() {
        $.ajax({
            url: location.href,
            data: {
                'roleid': {{$roleid}},
                "ids":getArray('id','checkbox'),
            },
            type: 'post',
            dataType: 'json',
            cache: false,
            async: true,
            success: function (res) {
                alert('操作成功')
            }
        });
    }
            </script>
        @endsection


