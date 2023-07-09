@extends('layouts.layout')

@section('layout')
    <style>
        .layui-form-checkbox{
            width: 225px;
        }
    </style>

                        <form style="margin-top: 15px;" action="{{route('admin.message.sendmessage')}}" name="f" >

                            @csrf


                            <table class="table">
                                <colgroup>
                                    <col width="180">
                                    <col>
                                </colgroup>
                                <tbody>


                                <tr>
                                <td>内容</td>
                                <td>
                                    <textarea id="content" name="content" style="width: 100%;height:300px;"></textarea>

                                </td>
                            </tr>

                            <tr>
                                <td>商户</td>
                                <td>
                                    <input type="checkbox"  onclick="ckall()" >全选

                                    @foreach($typeList as $k => $v)
                                        <fieldset class="layui-elem-field site-demo-button" style="margin-top: 5px;">
                                            <legend>{{$v}}</legend>
                                            <div>
                                                @if(isset($merchantResult[$k]))
                                                    @foreach($merchantResult[$k] as $k2 => $v2)
                                                        <input type="checkbox" lay-skin="primary" name="merchantid[]" value="{{$v2['merchantid']}}" title="{{$v2['name']}}" >
                                                    @endforeach
                                                @endif
                                            </div>
                                        </fieldset>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>谷歌校验码</td>
                                <td>
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off" class="layui-input">
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
    <script>
        function ckall(){
            var child = $("input[type='checkbox']");
            child.each(function (index, item) {
                item.checked = data.elem.checked;
            });
        }
    </script>
@endsection
