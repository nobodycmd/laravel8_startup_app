@extends('layouts.merchant')

@section('layout')


	<div class="panel panel-info">
		<div class="panel-heading">LOGO&产品名称 设置</div>
		<div class="panel-body">

			<form name="f"  action="{{route('merchant.logo.index')}}">

				<input type="hidden" value="{{$Merchant_info['product_logo']}}" name="product_logo" id="upload_url" >
			<table class="table">
				<colgroup>
					<col width="180">
					<col>
				</colgroup>
				<tbody>

							<tr>
								<td>产品名称</td><td>
									<input type="text" name="product_name"
										   autocomplete="off" class="form-control" value="{{$Merchant_info['product_name']}}">
								</td>
							</tr>

							<tr>
								<td>logo</td>
								<td>
									<input type="hidden" name="audit_credentials" id="upload_url" value="{{$Merchant_info['product_logo']}}">
									<a type="button" class="btn btn-success" id="upload">
										点击上传
									</a>
									<div  id="showFiles">
										<img src="{{$Merchant_info['product_logo']}}" />
									</div>
								</td>
							</tr>
							<tr>
								<td>logo</td>
								<td>
									<a class="btn btn-success" id="upload">点击选择</a>
									<div class="layui-carousel" id="dan" style="border: 1px solid #000;">
										<div  carousel-item id="danlist">

											<div ><img src="{{$Merchant_info['product_logo']}}" style="width:58px;height:58px;"></div>
										</div>
									</div>
								</td>
							</tr>

							<tr>
								<td>谷歌校验码</td>
								<td>
									<input type="text" name="gc" placeholder="请输入" autocomplete="off" class="form-control" id="google_authenticator_checkcode">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<a class="btn btn-success" onclick="commonPost('f')">保存</a>
									<button id="btnclear" type="reset"  class="btn btn-danger">清空设置</button>
								</td>
							</tr>
				</tbody>
			</table>

			</form>

		</div>
	</div>

@endsection

@section('script')
	<script>

		;!function(){
			var $ = layui.$
					,upload = layui.upload
					,carousel = layui.carousel;

			//X-CSRF-Token
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			//开启加载层
			$.openLoad = function(){
				return layer.load(1, {
					shade: [0.1, '#fff']
				});
			};

			//关闭加载层
			$.closeLoad = function(index){
				layer.close(index);
			};

			//显示弹出消息-提示框
			$.showPopupMsg = function(msg,callback){
				layer.msg(msg, {
					shade: [0.1, '#fff']
				},callback);
			};

			$("#total_fee").bind("keyup",function(){
				if($(this).val().substring(0,1)=="0" && $(this).val().substring(1,2)=="0"){
					$(this).val(0);
				}

				$(this).val($(this).val().replace(/^\./g,"")); //验证第一个字符是数字
				$(this).val($(this).val().replace(/[^\d.]/g,"")); //清除"数字"和"."以外的字符
				$(this).val($(this).val().replace(/\.{2,}/g,".")); //只保留第一个, 清除多余的
				$(this).val($(this).val().replace(".","$#$").replace(/\./g,"").replace("$#$","."));
				$(this).val($(this).val().replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3')); //只能输入两个小数

				return false;
			});

			$('#btnclear').on('click', function(){
				var openLoad = $.openLoad();

				$.ajax({
					url: "{{route('merchant.logo.index')}}"
					,data: {type:'clear_info',google_authenticator_checkcode:$('#google_authenticator_checkcode').val()}
					,type: 'post'
					,dataType: 'json'
					,cache: false
					,async: true
					,success: function(res){
						$.closeLoad(openLoad);
						if(res.code == 0){
							$.showPopupMsg(res.message,function(){
								top.location.reload();
							});
						}else{
							$.showPopupMsg(res.message);
						}
					}
				});
			});


			var dan = carousel.render({
				elem: '#dan'
				,width: '58px'
				,height: '58px'
				,arrow: 'none'
			});


			$("#upload").uploadFile({
				url:"{{route('admin.common.webFileUpload')}}",
				fileName:"file",
				maxFileCount:1,
				dragDrop:true,
				returnType: "json",
				statusBarWidth:600,
				dragdropWidth:600,
				maxFileSize:15000*1024,
				showPreview:true,
				previewHeight: "100px",
				previewWidth: "100px",
				onSuccess:function(files,res,xhr,pd)
				{
					console.log((res))
					$('#upload_url').val(res.data.url)
				},
			})



		}();

	</script>
@endsection
