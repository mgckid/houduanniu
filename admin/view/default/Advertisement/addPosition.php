<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading"></div>
    <div class="panel-body">
        <form class="form-horizontal" id="addPosition" action="<?=U('Advertisement/addPosition')?>" method="post">
            <input type="hidden" name="id" value="<?=$positionInfo['id']?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">广告位名称:</label>
                <div class="col-sm-10">
                    <input type="text" name="position_name" class="form-control" value="<?=$positionInfo['position_name']?>"  placeholder="请输入广告位名称">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告位标识:</label>
                <div class="col-sm-10">
                    <input type="text" name="position_key" class="form-control" value="<?=$positionInfo['position_key']?>"  placeholder="请输入广告位标识">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告位宽度:</label>
                <div class="col-sm-10">
                    <input type="text" name="ad_width" class="form-control" value="<?=$positionInfo['ad_width']?>"  placeholder="请输入广告位宽度">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告位高度:</label>
                <div class="col-sm-10">
                    <input type="text" name="ad_height" class="form-control" value="<?=$positionInfo['ad_height']?>"  placeholder="请输入广告位高度">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告位描述:</label>
                <div class="col-sm-10">
                    <textarea name="position_description" class="form-control"  rows="3" placeholder="请输入广告位描述"><?=$positionInfo['position_description']?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">操作</label>
                <div class="col-sm-10">
                    <button type="submit" data-power="Advertisement/addPosition" class="btn btn-success">添加</button>
                    <button type="reset" class="btn btn-danger ml10">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#addPosition').ajaxForm({
        dataType:'json',
        error: function () {
            layer.msg('服务器无法连接')
        },
        success: function (data) {
            layer.alert(data.msg)
        }
    })
</script>