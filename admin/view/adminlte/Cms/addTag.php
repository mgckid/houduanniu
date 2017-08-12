<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="<?= U('Cms/addTag') ?>" method="post" id="addTag" class="form-horizontal">
            <?php if (!empty($data['tag_id'])): ?>
                <input type="hidden" name="tag_id" value="<?=$data['tag_id']?>"/>
            <?php endif; ?>
            <div class="form-group">
                <label class="col-sm-1 control-label">标签名称:</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tag_name" value="<?=$data['tag_name']?>" placeholder="请输入标签名称">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">标签描述:</label>

                <div class="col-sm-10">
                    <textarea class="form-control" name="tag_description"  rows="3" placeholder="请输入标签描述"><?=$data['tag_description']?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">标签排序:</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?=$data['tag_sort']?>" name="tag_sort" placeholder="请输入标签排序">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" class="btn btn-success">保存</button>
                    <button type="reset" class="btn btn-danger">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#addTag').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器无法连接')
        },
        success: function (data) {
            layer.alert(data.msg)
            if (data.status == 1) {
                // window.location.href = '<?= U('Cms/Tag') ?>';
            }
        }
    })
</script>