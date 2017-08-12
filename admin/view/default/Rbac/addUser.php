<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form action="<?= U('Rbac/AddUser') ?>" name="addUser" class="form form-horizontal" method="post">
                <input type="hidden" name="id" value="<?= $info['id'] ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" value="<?= $info['username'] ?>" placeholder="点击输入用户名">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">真实姓名</label>

                    <div class="col-sm-10">
                        <input type="text" name="true_name" class="form-control" value="<?= $info['true_name'] ?>" placeholder="点击输入真实姓名">
                    </div>
                </div>
                <?php if (!$info['id']) { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">密码</label>

                        <div class="col-sm-10">
                            <input type="text" name="password" class="form-control" placeholder="点击输入密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">重复密码</label>

                        <div class="col-sm-10">
                            <input type="text" name="repassword" class="form-control" placeholder="点击输入密码">
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">邮箱</label>

                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" value="<?= $info['email'] ?>" placeholder="点击输入邮箱">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success " data-power="Rbac/AddUser">保存</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('form[name=addUser]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器无法连接');
        },
        success: function (data) {
            layer.alert(data.msg);
        }
    });
</script>