@extends('layouts.adminpop')

@section('layout')

    <div class="alert alert-warning">相同引用下的商户之间才可以进行转账；相同引用表明商户所有者是同一主体</div>

    <?php
    /** @var $merchant \App\Models\Merchant  */
        /** @var $myOthersMerchants \App\Models\Merchant[] */
    ?>
<form  name="f" action="{{route('merchant.merchant.innertransfer')}}">
            <table class="table">
                <colgroup>
                    <col width="230">
                    <col>
                </colgroup>
                <tbody>

                <tr>
                    <td>商户名称</td>
                    <td>{{$merchant->name}}</td>
                </tr>
                <tr>
                    <td>统一引用</td>
                    <td>
                        {{$merchant->identity}}
                    </td>
                </tr>
                <tr>
                    <td>商户余额</td>
                    <td>{{$merchant->balance}}</td>
                </tr>
                <tr>
                    <td>待下发</td>
                    <td>{{  \App\Services\AccountService::getWaitSettle($merchant->merchantid) }}</td>
                </tr>
                <tr>
                    <td>引用下其他商户</td>
                    <td>
                        <select name="merchantid" class="form-control">
                            @foreach($myOthersMerchants as $one)
                                <option value="{{$one->merchantid}}">{{$one->name}} 余额{{$one->balance}} 待下发{{  \App\Services\AccountService::getWaitSettle($one->merchantid) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>向同引用其他商户转账金额</td>
                    <td>
                        <input type="number" name="money" >
                    </td>
                </tr>

                <tr>
                    <td>谷歌验证码</td>
                    <td>
                        <input type="text" class="form-control" name="gc" required>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <a class="btn btn-success" onclick="commonPost('f')">确认转账</a>
                    </td>
                </tr>
                </tbody>
            </table>
</form>
@endsection

@section('script')
    <script>

    </script>
@endsection
