<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form class="form form-horizontal" name="add" action="<?= U('Operation/siteSet') ?>"   method="post">
                <input type="hidden" name="id" value="<?=$info['id']?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="site_name"  value="<?=$info['site_name']?>"   class="form-control" placeholder="点击输入站点名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点名称缩写</label>

                    <div class="col-sm-10">
                        <input type="text" name="short_site_name"  value="<?=$info['short_site_name']?>"   class="form-control" placeholder="点击输入站点名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">站点关键字</label>

                    <div class="col-sm-10">
                        <input type="text" name="site_keyword" value="<?=$info['site_keyword']?>"   class="form-control"  placeholder="点击输入站点关键字">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">站点描述</label>

                    <div class="col-sm-10">
                        <textarea name="site_description"  class=" form-control" rows="5" placeholder="点击输入站点描述"><?=$info['site_description']?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">创办时间</label>
                    <div class="col-sm-10">
                        <input type="text" name="found_time"   value="<?=$info['found_time']?>"  class="form-control"  placeholder="点击公站点创办时间">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button class="btn btn-success " data-power="Operation/siteSet">保存</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/panel-->
<!--时间选择 开始-->
<link rel="stylesheet" href="/static/common/dateTimePicker/jquery.datetimepicker.css"/>
<script src="/static/common/dateTimePicker/jquery.datetimepicker.full.js"></script>
<script>
    $('input[name=found_time]').datetimepicker({
        format:'Y-m-d'
    });
</script>
<!--时间选择 结束-->

<script type="text/javascript">
    $('form[name=add]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器连接失败');
        },
        success: function (data) {
            if (data.status == 1) {
                $('form').find('input:reset').click();
            }
            layer.alert(data.msg)
        }
    });
</script>