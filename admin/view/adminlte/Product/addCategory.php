<?php $this->layout('Layout/admin')?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form name="addColumn" action="<?= U('Product/addCategory') ?>" method="post"
                  class="form form-horizontal">
                <input type="hidden" name="category_id" value="<?= $info['category_id'] ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">所属栏目</label>

                    <div class="col-sm-10">
                        <select name="path" class="form-control">
                            <option value="">选择栏目</option>
                            <?php foreach ($list as $v) { ?>
                                <option  <?php echo $v['path'] == $info['path'] ? 'selected="selected"' : '' ?>  value="<?= $v['path'] ?>"><?= $v['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">栏目名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="category_name" class="form-control" value="<?= $info['category_name'] ?>" placeholder="点击输入栏目名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button data-power="Cms/addColumn" class="btn btn-success" type="submit">提交</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/panel-->
<script>


    $(function () {
        $('form[name=addColumn]').ajaxForm({
            dataType: 'json',
            success: function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    window.location.href = '<?= U('Product/Index') ?>';
                }
            }
        })
    })

</script>
