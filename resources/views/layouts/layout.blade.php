<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{config('config.title')}}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="robots" content="noindex,nofollow">

    <link href="/css/admin/skin/default/css/admin.css" type="text/css" rel="stylesheet"  />
    <link href="/javascript/artdialog/skins/twitter.css" type="text/css" rel="stylesheet"  />

    <script type='text/javascript' src="/javascript/jquery/jquery-1.12.4.min.js"></script>
    <script type='text/javascript' src="/javascript/artdialog/artDialog.js"></script>
    <script type='text/javascript' src="/javascript/artdialog/plugins/iframeTools.js"></script>
    <script type='text/javascript' src="/javascript/form/form.js"></script>
    <script type='text/javascript' src="/javascript/autovalidate/validate.js"></script>
    <script type='text/javascript' src="/javascript/artTemplate/artTemplate.js"></script>
    <script type='text/javascript' src="/javascript/echarts/echarts.simple.min.js"></script>
    <script type='text/javascript' src="/javascript/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type='text/javascript' src="/javascript/cookie/jquery.cookie.js"></script>
    <script type='text/javascript' src="//cdn.bootcss.com/admin-lte/2.4.3/js/adminlte.min.js"></script>
    <!-- 引入 layui.css -->
    <link rel="stylesheet" href="/css/layui/css/layui.css">
    <style>
        .pagination li {display:inline; margin-right:5px; padding:3px;}

        .pagination a {
            border: 1px solid #D5D5D5;
            color: #666666;
            font-size: 11px;
            font-weight: bold;
            height: 25px;
            padding: 4px 8px;
            text-decoration: none;
            margin:2px;
        }
        .pagination a:hover, .pagination a:active {
            background:#efefef;
        }
        .pagination span.current {
            background-color: #687282;
            border: 1px solid #D5D5D5;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            height: 25px;
            padding: 4px 8px;
            text-decoration: none;
            margin:2px;
        }
        .pagination span.disabled {
            border: 1px solid #EEEEEE;
            color: #DDDDDD;
            margin: 2px;
            padding: 2px 5px;
        }
    </style>

    <style>
        {{--   https://write.corbpie.com/bootstrap-sticky-first-column-horizontal-scrolling-table/       --}}
        .table {
            background: #fff;
            border-radius: 0.2rem;
            width: 100%;
            padding-bottom: 1rem;
            color: #212529;
            margin-bottom: 0;
        }

        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            background-color: #fff;
            color: #373737;
        }

        .table th:last-child,
        .table td:last-child {
            position: sticky;
            right: 0;
            background-color: #fff;
            color: #373737;
        }

        .table td {
            white-space: nowrap;
        }
    </style>
    <style>
        .layui-form input[type=checkbox], .layui-form input[type=radio], .layui-form select{
            /*display: inline-block !important;*/
        }
    </style>
    <!-- 引入 layui.js -->
    <script src="/css/layui/layui.js"></script>
    <link href="/css/layui/css/layui.css" type="text/css" rel="stylesheet"  />
    <script type='text/javascript' src="/javascript/layer/layer.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //开启加载层
        $.openLoad = function(){
            return layer.load(1, {
                shade: [0.1, '#188038']
            });
        };

        //关闭加载层
        $.closeLoad = function(index){
            layer.close(index);
        };

        //显示弹出消息-提示框
        $.showPopupMsg = function(msg,callback){
            layer.msg(msg, {
                shade: [0.1, '#188038']
            },callback);
        };
    </script>

    <script type='text/javascript' src="/javascript/public.js?md5=<?= md5_file(public_path('/javascript/public.js')) ?>"></script>

    <style>
        .table-header-fixed {
            top: 0;
            position: fixed;
            z-index: 999;
        }
    </style>

    <style>
        .skin-blue .sidebar-menu>li>.treeview-menu{
            max-height: 200px;
            overflow-y: scroll !important;
            scrollbar-color: rebeccapurple green;
            scrollbar-width: thin;
        }
        .skin-blue .sidebar-menu>li>.treeview-menu::-webkit-scrollbar {
            width: 10px;
        }
        .skin-blue .sidebar-menu>li>.treeview-menu::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 5px;
        }
    </style>
</head>

<body class="skin-blue fixed sidebar-mini" style="height: auto; min-height: 100%;">
<div class="wrapper" style="height: auto; min-height: 100%;">
    <header class="main-header" id="main-header">
        <div class="logo">
            <span class="logo-mini"><img src="/img/logo.png"></span>
            <span class="logo-lg"><img src="/img/logo.png">后台管理</span>
        </div>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only"></span>
            </a>

            <!--顶部菜单 开始-->
            <div id="menu" class="navbar-custom-menu">
                <ul class="nav navbar-nav" name="topMenu">
                    <li>
                        <a href="{{route('admin.devtool.tables')}}" >开发</a>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
            <!--顶部菜单 结束-->
        </nav>
    </header>
    <script>
        if(top.window != window){
            document.getElementById('main-header').style.display = 'none'
        }
    </script>

    <!--左侧菜单 开始-->
    <aside id="admin_left" class="main-sidebar">
        <section class="sidebar" style="height: auto;">
            <div class="user-panel">
                <div class="pull-left image">
                    <i class="fa fa-user"></i>
                </div>
                <div class="pull-left info">
                    <p>{{auth('admin')->user()?auth('admin')->user()->name:'未登陆'}}</p>
                    <a href="#" id="__time"></a>
                </div>
            </div>

            <ul class="sidebar-menu tree" data-widget="tree">

                @foreach(request()->get('menus',[]) as $lk => $lv)
                <li class="treeview">
                    <a href="#">
                        <i class="fa" name="ico" menu="{{$lv['name']}}"></i>
                        <span>{{$lv['name']}}</span>
                        <span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
                    </a>
                    <ul class="treeview-menu" name="leftMenu">
                        @foreach($lv['childList'] as $clk => $clv)
                        <li><a href="{{route($clv['uri'])}}"><i class="fa fa-circle-o"></i>{{$clv['name']}}</a></li>
                        @endforeach
                    </ul>
                </li>
                @endforeach

{{--                <li class="header">快速导航</li>--}}
{{--                <li><a href="href"><i class="fa fa-star-o"></i> <span>name</span></a></li>--}}
            </ul>
        </section>
    </aside>
    <!--左侧菜单 结束-->
    <script>
        if(top.window != window){
            document.getElementById('admin_left').style.display = 'none'
        }
    </script>

    <!--右侧内容 开始 padding-top:80px; -->
    <div id="admin_right" class="content-wrapper" style="font-size: 16px;background-color:#fff;">

<?php
//        return redirect($url)->with('pageTip', $tip);
?>

        @if (session('pageTip'))
            <div class="alert alert-info">{{session('pageTip')}}</div>
        @endif

        @yield('layout')
    </div>
    <!--右侧内容 结束-->
    <script>
        if(top.window != window){
            document.getElementById('admin_right').className=''
            document.getElementById('admin_right').style.paddingTop = '5px'
        }
    </script>

    <!--顶部弹出菜单 开始-->
    <aside class="control-sidebar control-sidebar-dark">
        <ul class="control-sidebar-menu">
            <li><a href="{{route('admin.index.password')}}" style="color: #fff;"><i class="fa fa-circle-o text-yellow"></i> 修改密码</a></li>
            <li><a href="{{route('admin.index.logout')}}" style="color: #fff;"><i class="fa fa-circle-o text-red"></i> 退出管理</a></li>
        </ul>
    </aside>
    <!--顶部弹出菜单 结束-->
</div>
<script>
    //得到标准时区的时间的函数
    //参数i为时区值数字，比如北京为东八区则输进8,西5输入-5
    function getLocalTime(i) {
        if (typeof i !== 'number') return;

        var d = new Date();
        //得到1970年一月一日到现在的秒数
        var len = d.getTime();

        //本地时间与GMT时间的时间偏移差(注意：GMT这是UTC的民间名称。GMT=UTC）
        var offset = d.getTimezoneOffset() * 60000;

        //得到现在的格林尼治时间
        var utcTime = len + offset;

        return new Date(utcTime + 3600000 * i);
    }

    setInterval(function (){
        $('#__time').text(getLocalTime(5.5).toLocaleString())
    },1000)
</script>


<script type='text/javascript'>
    //图标配置
    var icoConfig = {
        "面板":"fa-inbox","上下分":"fa-list","订单模块":"fa-registered","商户&代理":"fa-cubes","通道和银行":"fa-search","软件&系统":"fa-user-o","财务管理":"fa-group","换汇管理":"fa-comment-o",
        "订单管理":"fa-file-text","单据管理":"fa-files-o","发货地址":"fa-address-card-o","促销活动":"fa-bullhorn","营销活动":"fa-bell-o",
        "优惠券管理":"fa-ticket","基础数据统计":"fa-bar-chart-o","后台首页":"fa-home","日志管理":"fa-file-code-o",
        "商户数据统计":"fa-pie-chart","支付模块":"fa-credit-card","第三方平台":"fa-share-alt","配送管理":"fa-truck","流水文件":"fa-street-view",
        "权限管理":"fa-unlock-alt","开发工具":"fa-database","文章管理":"fa-file-o","帮助管理":"fa-question-circle-o",
        "广告管理":"fa-flag","公告管理":"fa-bookmark-o","网站地图":"fa-sitemap","插件管理":"fa-cogs","通道模块":"fa-wrench"
    };
    $('i[name="ico"]').each(function()
    {
        var menuName = $(this).attr('menu');
        if(menuName && icoConfig[menuName])
        {
            $(this).addClass(icoConfig[menuName]);
        }
        else
        {
            //默认图标
            $(this).addClass("fa-circle");
        }
    });

    //兼容IE系列
    $("[name='leftMenu'] [href^='javascript:']").each(function(i)
    {
        var fun = $(this).attr('href').replace("javascript:","");
        $(this).attr('href','javascript:void(0)');
        $(this).on("click",function(){eval(fun)});
    });

    //按钮高亮
    var topItem = "{echo:$modelName}";
    $("[name='topMenu']>li:contains('"+topItem+"')").addClass("active");

    //获取左侧菜单项
    function matchLeftMenu(leftItem)
    {
        var matchObject = $('[name="leftMenu"]>li a[href="'+leftItem+'"]');
        if(matchObject.length > 0)
        {
            $.cookie('lastUrl', leftItem);
            return matchObject;
        }

        var lastUrl = $.cookie('lastUrl');
        if(lastUrl)
        {
            return $('[name="leftMenu"]>li a[href="'+lastUrl+'"]');
        }
        return null;
    }

    //左边栏菜单高亮
    var leftItem   = location.href;
    var matchObject = matchLeftMenu(leftItem);
    if(matchObject)
    {
        matchObject.parent().addClass("active").parent('ul').show().parent('.treeview').addClass('menu-open');
    }
</script>
<script>
    $(document).ready(function (){
        layui.form.on('submit(form-submit)', function(obj){
            var openLoad = $.openLoad();
            var refresh =   $(this).attr('form-submit-refresh')
            $.ajax({
                url: $(this).attr('form-submit-url')
                ,data: obj.field
                ,type: 'post'
                ,dataType: 'json'
                ,cache: false
                ,async: true
                ,success: function(res){
                    $.closeLoad(openLoad);
                    if(('code' in res) && res.code == 0){
                        $.showPopupMsg(res.message,function(){
                            console.log(refresh)
                            if(parseInt(refresh) == 1){

                                location.reload();
                            }else{

                                parent.location.reload();
                            }
                        });
                    }else{
                        $.showPopupMsg(res.message);
                    }
                },
                error:function(res){
                    $.closeLoad(openLoad);
                    console.log(res.message);
                    $.showPopupMsg('出错了,重新提交');
                }
            });
            return false;
        });
    })
</script>
@yield('script')
</body>
</html>
