@extends('layouts.agent')
@section('layout')
    <div class="container-fluid" style="padding-top:15px;">
        <form method="post" class="form-inline">
        @csrf
        <!-- <div class="form-group">
    <label>id</label>
    <input type="text" class="form-control" name="id" id="id"  autocomplete="false" placeholder="id" value="{{request()->input('id','')}}">
</div> -->


        <!-- <div class="form-group">
    <label>代收交易金额</label>
    <input type="text" class="form-control" name="in_total_fee" id="in_total_fee"  autocomplete="false" placeholder="代收交易金额" value="{{request()->input('in_total_fee','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>代收订单数</label>
    <input type="text" class="form-control" name="in_count" id="in_count"  autocomplete="false" placeholder="代收订单数" value="{{request()->input('in_count','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>代收结算金额</label>
    <input type="text" class="form-control" name="in_settlement_fee" id="in_settlement_fee"  autocomplete="false" placeholder="代收结算金额" value="{{request()->input('in_settlement_fee','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>订单创建时间</label>
    <input type="text" class="form-control" name="order_create_time" id="order_create_time"  autocomplete="false" placeholder="订单创建时间" value="{{request()->input('order_create_time','')}}">
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
    <label>代付交易金额</label>
    <input type="text" class="form-control" name="out_total_fee" id="out_total_fee"  autocomplete="false" placeholder="代付交易金额" value="{{request()->input('out_total_fee','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>代付订单数</label>
    <input type="text" class="form-control" name="out_count" id="out_count"  autocomplete="false" placeholder="代付订单数" value="{{request()->input('out_count','')}}">
</div> -->

        <!-- <div class="form-group">
    <label>代付结算金额</label>
    <input type="text" class="form-control" name="out_settlement_fee" id="out_settlement_fee"  autocomplete="false" placeholder="代付结算金额" value="{{request()->input('out_settlement_fee','')}}">
</div> -->



        <!-- <div class="form-group">
    <label>订单号</label>
    <input type="text" class="form-control" name="order_number" id="order_number"  autocomplete="false" placeholder="订单号" value="{{request()->input('order_number','')}}">
</div> -->

         <div class="form-group">
    <label>商户id</label>
    <input type="text" class="form-control" name="merchantid" id="merchantid"  autocomplete="false" placeholder="商户id" value="{{request()->input('merchantid','')}}">
</div>


            <input type="submit" class="btn btn-success"/>
        </form>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th>id</th>

                    <th>代理号</th>

                    <th>代收交易金额</th>

                    <th>代收订单数</th>

                    <th>代收结算金额</th>

                    <th>订单创建时间</th>

                    <th>创建时间</th>

                    <th>更新时间</th>

                    <th>代付交易金额</th>

                    <th>代付订单数</th>

                    <th>代付结算金额</th>


                    <th>订单号</th>

                    <th>商户id</th>


                </tr>

                <?php
                /** @var \App\Models\AgentSettlementNew $one */
                foreach($list as $one){ ?>
                <tr>
                    <td>{{$one->id}}</td>
                    <td>{{$one->agent_id}}</td>
                    <td>{{$one->in_total_fee}}</td>
                    <td>{{$one->in_count}}</td>
                    <td>{{$one->in_settlement_fee}}</td>
                    <td>{{$one->order_create_time}}</td>
                    <td>{{$one->create_time}}</td>
                    <td>{{$one->update_time}}</td>
                    <td>{{$one->out_total_fee}}</td>
                    <td>{{$one->out_count}}</td>
                    <td>{{$one->out_settlement_fee}}</td>

                    <td>{{$one->order_number}}</td>
                    <td>{{$one->merchantid}}</td>

                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    {{$paginator->links()}}
@endsection


@section('script')
@endsection