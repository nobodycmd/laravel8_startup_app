@extends('layouts.adminpop')

@section('layout')
    
                        <form  style="margin-top: 15px;" action="{{route('admin.merchant.balance')}}">
                            @csrf

                            <input type="hidden" name="id" value="{{$merchant['id']}}">

                            <tr >
                                <td>商户号</td>
                                <td>
                                    {{$merchant['merchantid']}}
                                </td>
                            </tr>
                            <tr >
                                <td>商户名称</td>
                                <td>
                                    {{$merchant['name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>类型</td>
                                <td>
                                    <select name="type" id="type"  class="form-control">
                                        <option value="1">增加余额</option>
                                        <option value="-1">减少余额</option>
                                    </select>
                                </td>
                            </tr>
                            <tr >
                                <td>交易金额</td>
                                <td>
                                    <input type="text" name="total_fee" placeholder="请输入" autocomplete="off" class="form-control" id="total_fee">
                                    <br>
                                    <span id="total_fee_show"></span>
                                </td>
                            </tr>
                            <tr >
                                <td>备注</td>
                                <td>
                                    <input type="text" name="remark" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr >
                                <td>谷歌校验码</td>
                                <td>
                                    <input type="text" name="gc" placeholder="请输入" autocomplete="off" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-success" >保存</button>
                                    <button type="reset" class="btn btn-primary">重置</button>
                                </td>
                            </tr>
                        </form>
              
                        
        
                            <div>历史记录</div>
                            <div class="table-responsive">
                                <table class="table">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>商户号</th>
                                        <th>交易金额</th>
                                        <th>备注</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>管理员姓名</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($merchantAccountLogList as $k => $v)
                                        <tr>
                                            <td>{{$v['merchantid']}}</td>
                                            <td>{{$v['total_fee']}}</td>
                                            <td>{{$v['remark']}}</td>
                                            <td>{{$v['status']}}</td>
                                            <td>{{$v['create_time']}}</td>
                                            <td>{{$v['admin_name']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
@endsection

@section('script')
    <script>


            function changeMoneyToChinese(money){
                var cnNums = new Array("零","一","二","三","四","五","六","七","八","九"); //汉字的数字
                var cnIntRadice = new Array("","十","百","千"); //基本单位
                var cnIntUnits = new Array("","万","亿","兆"); //对应整数部分扩展单位
                var cnDecUnits = new Array("角","分","毫","厘"); //对应小数部分单位
                var cnIntLast = ""; //整型完以后的单位
                var maxNum = 999999999999999.999; //最大处理的数字

                var IntegerNum; //金额整数部分
                var DecimalNum; //金额小数部分
                var ChineseStr=""; //输出的中文金额字符串
                var parts; //分离金额后用的数组，预定义
                if(money == ""){
                    return "";
                }

                money = parseFloat(money);

                if(money >= maxNum){
                    return "超出最大处理数字";
                }

                if(money == 0){
                    ChineseStr = cnNums[0]+cnIntLast
                    return ChineseStr;
                }

                money = money.toString(); //转换为字符串

                if(money.indexOf(".") == -1){
                    IntegerNum = money;
                    DecimalNum = '';
                }else{
                    parts = money.split(".");
                    IntegerNum = parts[0];
                    DecimalNum = parts[1].substr(0,4);
                }

                if(parseInt(IntegerNum,10) > 0){//获取整型部分转换
                    zeroCount = 0;
                    IntLen = IntegerNum.length;
                    for(i=0;i<IntLen;i++){
                        n = IntegerNum.substr(i,1);
                        p = IntLen - i - 1;
                        q = p / 4;
                        m = p % 4;
                        if(n == "0"){
                            zeroCount++;
                        }else{
                            if(zeroCount > 0){
                                ChineseStr += cnNums[0];
                            }
                            zeroCount = 0; //归零
                            ChineseStr += cnNums[parseInt(n)]+cnIntRadice[m];
                        }
                        if(m==0 && zeroCount<4){
                            ChineseStr += cnIntUnits[q];
                        }
                    }
                    ChineseStr += cnIntLast;//整型部分处理完毕
                }

                if(DecimalNum!= ''){//小数部分
                    decLen = DecimalNum.length;
                    for( i=0; i<decLen; i++ ){
                        n = DecimalNum.substr(i,1);
                        if(n != '0'){
                            ChineseStr += cnNums[Number(n)]+cnDecUnits[i];
                        }
                    }
                }

                if(ChineseStr == ''){
                    ChineseStr += cnNums[0]+cnIntLast;
                }

                return ChineseStr;
            }

            $("#total_fee").bind("keyup",function(){
                if($(this).val().substring(0,1)=="0" && $(this).val().substring(1,2)=="0"){
                    $(this).val(0);
                }
                $(this).val($(this).val().replace(/^\./g,"")); //验证第一个字符是数字
                $(this).val($(this).val().replace(/[^\d.]/g,"")); //清除"数字"和"."以外的字符
                $(this).val($(this).val().replace(/\.{2,}/g,".")); //只保留第一个, 清除多余的
                $(this).val($(this).val().replace(".","$#$").replace(/\./g,"").replace("$#$","."));
                $(this).val($(this).val().replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3')); //只能输入两个小数

                $('#total_fee_show').html(changeMoneyToChinese($(this).val()));
            });


    </script>
@endsection
