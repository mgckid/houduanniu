<?php $this->Layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form action="<?= U('Operation/addFlink') ?>" name="addUser" class="form form-horizontal" method="post">
                <input name="id" type="hidden" value="<?= $info['id'] ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="fname" class="form-control" value="<?= $info['fname'] ?>" placeholder="点击输入站点名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点链接</label>

                    <div class="col-sm-10">
                        <input type="text" name="furl" class="form-control"  value="<?= $info['furl'] ?>" placeholder="点击输入站点链接">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点描述</label>

                    <div class="col-sm-10">
                        <textarea  class="form-control" name="fdesc"   placeholder="点击输入站点描述"><?= $info['fdesc'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button type="submit" data-power="Operation/addFlink" class="btn btn-success ">保存</button>
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