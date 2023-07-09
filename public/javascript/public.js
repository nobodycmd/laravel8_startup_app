//全选
function selectAll(nameVal)
{
	if($("input[type='checkbox'][name^='"+nameVal+"']:not(:checked)").length > 0)
	{
		$("input[type='checkbox'][name^='"+nameVal+"']").prop('checked',true);
	}
	else
	{
		$("input[type='checkbox'][name^='"+nameVal+"']").prop('checked',false);
	}
}
/**
 * @brief 获取控件元素值的数组形式
 * @param string nameVal 控件元素的name值
 * @param string sort    控件元素的类型值:checkbox,radio,text,textarea,select
 * @return array
 */
function getArray(nameVal,sort)
{
	//要ajax的json数据
	var jsonData = new Array;

	switch(sort)
	{
		case "checkbox":
		$('input[type="checkbox"][name="'+nameVal+'"]:checked').each(
			function(i)
			{
				jsonData[i] = $(this).val();
			}
		);
		break;
	}
	return jsonData;
}
window.loadding = function(message){var message = message ? message : '正在执行，请稍后...';art.dialog({"id":"loadding","lock":true,"fixed":true,"drag":false}).content(message);}
window.unloadding = function(){art.dialog({"id":"loadding"}).close();}
window.tips = function(mess){art.dialog.tips(mess);}
window.alert2 = function(mess){art.dialog.alert(String(mess));}
window.confirm = function(mess,bnYes,bnNo)
{
	art.dialog.confirm(
		String(mess),
		function(){typeof bnYes == "function" ? bnYes() : bnYes && (bnYes.indexOf('/') == 0 || bnYes.indexOf('http') == 0) ? window.location.href=bnYes : eval(bnYes);},
		function(){typeof bnNo == "function" ? bnNo() : bnNo && (bnNo.indexOf('/') == 0 || bnNo.indexOf('http') == 0) ? window.location.href=bnNo : eval(bnNo);}
	);
}
/**
 * @brief 删除操作
 * @param object conf
	   msg :提示信息;
	   form:要提交的表单名称;
	   link:要跳转的链接地址;
 */
function delModel(conf)
{
	var ok = null;            //执行操作
	var msg= '确定要删除么？';//提示信息

	if(conf)
	{
		if(conf.form)
		{
			var ok = 'formSubmit("'+conf.form+'")';
			if(conf.link)
			{
				var ok = 'formSubmit("'+conf.form+'","'+conf.link+'")';
			}
		}
		else if(conf.link)
		{
			var ok = 'window.location.href="'+conf.link+'"';
		}

		if(conf.msg)
		{
			var msg = conf.msg;
		}

		if(conf.name && checkboxCheck(conf.name,"请选择要操作项") == false)
		{
			return '';
		}
	}
	if(ok==null && document.forms.length >= 1)
		var ok = 'document.forms[0].submit();';

	if(ok!=null)
	{
		window.confirm(msg,ok);
	}
	else
	{
		alert('删除操作缺少参数');
	}
}

//根据表单的name值提交
function formSubmit(formName,url)
{
	if(url)
	{
		$('form[name="'+formName+'"]').attr('action',url);
	}
	$('form[name="'+formName+'"]').submit();
}

//根据checkbox的name值检测checkbox是否选中
function checkboxCheck(boxName,errMsg)
{
	if($('input[name="'+boxName+'"]:checked').length < 1)
	{
		alert(errMsg);
		return false;
	}
	return true;
}

//倒计时
var countdown=function()
{
	var _self=this;
	this.handle={};
	this.parent={'second':'minute','minute':'hour','hour':""};
	this.add=function(id)
	{
		_self.handle.id=setInterval(function(){_self.work(id,'second');},1000);
	};
	this.work=function(id,type)
	{
		if(type=="")
		{
			return false;
		}

		var e = document.getElementById("cd_"+type+"_"+id);
		var value=parseInt(e.innerHTML);
		if( value == 0 && _self.work( id,_self.parent[type] )==false )
		{
			clearInterval(_self.handle.id);
			return false;
		}
		else
		{
			e.innerHTML = (value==0?59:(value-1));
			return true;
		}
	};
};

/*实现事件页面的连接*/
function event_link(url)
{
	window.location.href = url;
}

//延迟执行
function lateCall(t,func)
{
	var _self = this;
	this.handle = null;
	this.func = func;
	this.t=t;

	this.execute = function()
	{
		_self.func();
		_self.stop();
	}

	this.stop=function()
	{
		clearInterval(_self.handle);
	}

	this.start=function()
	{
		_self.handle = setInterval(_self.execute,_self.t);
	}
}

function commonOpen($dom) {
	var title = $($dom).text()
	var $url = $($dom).attr('url')

	if(!title ){title='弹窗'}
	layer.open({
		type: 2,
		title: title,
		shadeClose: true,
		shade: false,
		maxmin: true, //开启最大化最小化按钮
		area: ['1020px', '650px'],
		content: $url
	});
}

function postDirectlyWithGoogleCode($domOrUrl,callback){
	var $url = ''
	if(typeof $domOrUrl == typeof 'str' && $domOrUrl.indexOf('http') == 0){
		$url = $domOrUrl
	}else{
		$url = $($domOrUrl).attr('url')
	}
	layer.prompt({
		title: '谷歌校验码'
		,shade: [0.1, '#fff']
		,closeBtn: 0
		,move: false
	}, function(value, index, elem){
		$.ajax({
			url: $url,
			data: "gc="+value,
			type: 'post',
			dataType: 'json',
			cache: false,
			async: true,
			success: function (res) {
				console.log(res)
				if(callback){callback(res)}
				else{
					if(res.message){
						alert(res.message)
					}else {
						alert('操作成功')
					}
				}
			}
		});
	});
}

function commonOpenForTr($dom) {
	var found = false,i = 0,params={};
	var title = $($dom).text()
	var $url = $($dom).attr('url')

	do{
		if($url.indexOf('?id=')){
			break;
		}else {
			$url += '?'

			if (Number($($dom).parent().attr('data-index')) == $($dom).parent().attr('data-index')) {
				found = true
				i = $($dom).parent().attr('data-index')
				var tds = $('.layui-table-body').eq(0).find('tr[data-index=' + i + ']').find('td')
				var haveid = false
				for (var j = 0; j < tds.length; j++) {
					if ('id' == $(tds[j]).attr('data-field')) {
						haveid = true
						$url += '&id=' + ($(tds[j]).text())
					}
				}

				if (!haveid) {
					for (var j = 0; j < tds.length; j++) {
						if (Number($(tds[j]).attr('data-field')) != $(tds[j]).attr('data-field')) {
							$url += '&' + $(tds[j]).attr('data-field') + '=' + ($(tds[j]).text())
						}
					}
				}
				break
			} else {
				$dom = $($dom).parent()
			}
		}
	}while (!found)

	if(!title ){title='弹窗'}
	layer.open({
		type: 2,
		title: title,
		shadeClose: true,
		shade: false,
		maxmin: true, //开启最大化最小化按钮
		area: ['893px', '600px'],
		content: $url
	})
}

function commonEditFormOpen($url,title) {
	if(!title ){title='弹窗'}
	art.dialog.open($url, {
		"id": "www",
		"title": title,
		"ok": function (iframeWin, topWin) {
			var formObject = iframeWin.document.forms[0];
			// if (formObject.onsubmit() === false) {
			// 	alert("请正确填写各项信息");
			// 	return false;
			// }
			//
			$.ajax({
				url: formObject.action,
				data: $(formObject).serialize(),
				type: 'post',
				dataType: 'json',
				cache: false,
				async: true,
				success: function (res) {
					if (res.code == 0) {
						alert('成功')
						if(callback)callback()
					} else {
						alert(res.message);
					}
					art.dialog({"id": "www"}).close();
				}
			});
			return false;
		},
		"okVal": "Okay",
		"cancel": true,
		"cancelVal": "取消",
	});
}

var posting = false
function commonPost(formname,callback){
	if(posting){
		alert('正在操作中')
		return
	}

	posting = true
	$.ajax({
		url: document.forms[formname].action,
		data: $(document.forms[formname]).serialize(),
		type: 'post',
		dataType: 'json',
		cache: false,
		async: true,
		success: function (res) {
			posting = false

			if (res.code == 0) {
				if(callback){
					callback(res)
				}else{
					alert('完成操作')
					if(window != top.window){
						top.window.location = top.window.location
					}
				}
			} else {
				alert(res.message);
			}
		},error:function (r) {
			posting = false
		}
	});
}