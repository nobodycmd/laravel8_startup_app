
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">

    <title>管理中心</title>
    <link href="/css/twitter-bootstrap/3.3.7/css/bootstrap.min.css" type="text/css"  rel='stylesheet'  media="all"/>
    <link href="/css/uni/skin/default/css/login.css" type="text/css"  rel='stylesheet' media="all" />
    <link href="/javascript/artdialog/skins/twitter.css" type="text/css" rel="stylesheet"  />

    <script type='text/javascript' src="/javascript/jquery/jquery-1.12.4.min.js"></script>
    <script type='text/javascript' src="/javascript/artdialog/artDialog.js"></script>
    <script type='text/javascript' src="/javascript/artdialog/plugins/iframeTools.js"></script>
    <script type='text/javascript' src='/javascript/public.js'></script>
</head>

<body>
<header class="clearfix">
    <div class="col-md-12 column">
        <ul class="breadcrumb">
            <li><a href="###">出海支付</a> <span class="divider"></span></li>
            <li class="active">系统</li>
        </ul>
    </div>
</header>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <h3 class="text-center w700" style="color:#333">后台系统</h3>
                    <form method="post" name="login" action="{{route('admin.index.login')}}" autoComplete="off" class="form-horizontal" role="form">
                        @csrf
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form-control" placeholder="用户名" name="username" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="password" class="form-control" placeholder="密码" type="password" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form-control" placeholder="谷歌验证码" name="gc" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class=" col-sm-12">
                                <button type="button" id="btn" class="btn btn-block btn-success">登录</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var doing = false
    var formname = 'login'
    $('#btn').click(function () {
        if(doing)return

        doing = true
        $.ajax({
            url: document.forms[formname].action,
            data: $(document.forms[formname]).serialize(),
            type: 'post',
            dataType: 'json',
            cache: false,
            async: true,
            success: function (res) {
                doing = false
                if (res.code == 0) {
                    location = "{{route('admin.homepage.console')}}"
                } else {
                    alert(res.message);
                }
            },error:function (r) {
                doing = false
            }
        });
    })
</script>
</body>
</html>



