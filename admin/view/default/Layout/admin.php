<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title> <?=$siteInfo['short_site_name']?>后台管理系统</title>
    <link rel="stylesheet" href="/static/admin/default/css/public.css">
    <script src="/static/common/jquery/jquery-1.11.3.min.js"></script>
    <script src="/static/common/bootstrap-3.3.5-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/static/common/bootstrap-3.3.5-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/static/common/font-awesome-4.4.0/css/font-awesome.css"/>
    <script src="/static/common/layer-v2.1/layer/layer.js"></script>
    <script src="/static/common/ajaxForm/jquery.form.js"></script>
    <link rel="stylesheet" href="/static/admin/default/css/admin.css">
</head>
<body>
<?= $this->insert('Common/header') ?>
<!-- /header -->
<?= $this->insert('Common/menu') ?>
<!-- /menu -->
<div class="content-box">
    <ol class="breadcrumb">
        <?= $crumbs ?>
    </ol>
    <?= $this->section('content') ?>
</div>
<!-- /content-box -->
</div>
<!-- /main -->
<?= $this->insert('Common/footer') ?>
<!-- /footer -->
<script>
    function logout() {
        $.post('<?= U('Login/logout') ?>', {'logout': true}, function (data) {
            if (data.status == 1) {
                window.location.reload();
            }
        }, 'json')
    }
</script>
<!--检查权限 开始-->
<?= $this->insert('Common/checkPower') ?>
<!--检查权限 结束-->

</body>
</html>