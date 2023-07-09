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
{{--    <script type='text/javascript' src="//cdn.bootcss.com/admin-lte/2.4.3/js/adminlte.min.js"></script>--}}

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
            display: inline-block !important;
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

    <!-- https://hayageek.com/docs/jquery-upload-file.php#features -->
    <link href="/css/jqueryupload/jquery-file-upload.css" type="text/css" rel="stylesheet"  />
    <script src="/css/jqueryupload/jquery-file-upload.js"></script>
    <style>
        .table-header-fixed {
            top: 0;
            position: fixed;
            z-index: 999;
        }
    </style>
</head>

<body class="skin-blue fixed sidebar-mini" style="height: auto; min-height: 100%;">
<div class="wrapper" style="height: auto; min-height: 100%;background-color:#fff;">


    <!--右侧内容 开始 padding-top:80px; -->
    <div id="admin_right"  style="font-size: 16px;padding-top: 5px;">

        @if (session('pageTip'))
            <div class="alert alert-info">{{session('pageTip')}}</div>
        @endif


        @yield('layout')
    </div>


</div>


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
