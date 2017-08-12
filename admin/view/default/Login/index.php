<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>登录</title>
        <link rel="stylesheet" href="/static/admin/default/css/public.css">
        <script src="/static/common/jquery/jquery-1.11.3.min.js"></script>
        <script src="/static/common/bootstrap-3.3.5-dist/js/bootstrap.js"></script>
        <link rel="stylesheet" href="/static/common/bootstrap-3.3.5-dist/css/bootstrap.css">
        <link rel="stylesheet" href="/static/common/font-awesome-4.4.0/css/font-awesome.css"/>
        <script src="/static/common/layer-v2.1/layer/layer.js"></script>
        <script src="/static/common/ajaxForm/jquery.form.js"></script>
        <link rel="stylesheet" href="/static/admin/default/css/admin.css">
    </head>
    <body class="bg-ruanpower">

        <div id="manage-login">
            <div class="panel panel-success">
                <div class="panel-heading">网站系统管理</div>
                <div class="panel-body">
                    <form id="loginform"  action="<?= U('Login/index') ?>" method="POST">
                        <div class="form-group">
                            <label for="exampleInputEmail1">用户名</label>
                            <input type="text" name="username" class="form-control"  placeholder="用户名">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">密码</label>
                            <input type="password" name="password" class="form-control"  placeholder="密码">
                        </div>
                        <input type="submit"  class="btn btn-primary"/>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                var top = ($(window).height() - $('#manage-login').outerHeight()) / 2;
                $('#manage-login').css("margin-top", top);
            });
            $(window).on('scroll resize', function () {
                var top = ($(window).height() - $("#manage-login").outerHeight()) / 2;
                $('#manage-login').css("margin-top", top);
            });
            //提交登陆
            $('#loginform').bind('submit', function (e) {
                e.preventDefault();
                $(this).ajaxSubmit({
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 1) {
                            layer.alert(data.msg);
                            window.location.href = '<?= U('Index/index') ?>';
                        } else {
                            layer.msg(data.msg)
                        }
                    }
                })
            })

        </script>
    </body>
</html>