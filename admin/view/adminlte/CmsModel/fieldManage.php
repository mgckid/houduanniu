<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box mb10">
        </div>
        <table class="table ">
            <tr>
                <?php foreach ($list_init as $value):?>
                    <th><?=$value['name']?></th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            <?php foreach ($list as $value): ?>
                <tr>
                    <?php foreach ($list_init as $key => $val): ?>
                        <td><?= isset($val['enum'][$value[$key]]) ? $val['enum'][$value[$key]] : $value[$key] ?></td>
                    <?php endforeach;?>
                    <td>
                        <a class="btn btn-success btn-xs"  href="<?= U('CmsModel/addField', array('id' => $value['id'])) ?>" data-power="CmsModel/addField">编辑</a>
                        <a class="btn btn-info btn-xs"  href="<?= U('CmsModel/addAttribute', array('field_id' => $value['id'])) ?>" data-power="CmsModel/addAttribute">添加属性</a>
                        <?php if($value['attr_count']>0):?>
                            <a class="btn btn-default btn-xs"  href="<?= U('CmsModel/attributeManage', array('field_id' => $value['id'])) ?>" data-power="CmsModel/attributeManage">属性管理</a>
                        <?php endif;?>
                        <a class="btn btn-danger ml10 btn-xs" href="javascript:void(0)" onclick="delRecord(<?= $value['id'] ?>)" data-power="CmsModel/delRecord">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script>
    //删除记录
    function delRecord(id) {
        layer.confirm('您确定要删除选中的记录么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U('CmsModel/delRecord') ?>', {id: id}, function (data) {
                layer.msg(data.msg);
                if (data.status == 1) {
                    $("#row" + id).remove();
                }
            }, 'json');

        }, function () {
            return
        });
    }
</script>