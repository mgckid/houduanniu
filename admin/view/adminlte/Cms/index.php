<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box mb10">
            <a class="btn btn-success btn-sm" data-power="Cms/addCategory" href="<?= U('Cms/addCategory') ?>">添加栏目</a>
        </div>
        <table class="table">
            <tr>
                <?php foreach ($list_init as $key=> $value):?>
                    <th><?=$value['field_name']?></th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            <?php foreach ($list as  $val) {?>
                <tr>
                    <?php foreach ($list_init as $key => $value): ?>
                        <td><?= $val[$key] ?></td>
                    <?php endforeach;?>
                    <td>
                        <a class="btn btn-primary btn-xs" data-power="Cms/postList" href="<?= U('Cms/postList', array('category_id' => $val['id'])) ?>">查看内容</a>
                        <a class="btn btn-success btn-xs" data-power="Cms/addPost" href="<?= U('Cms/addPost', array('category_id' => $val['id'])) ?>">添加文档</a>
                        <a class="btn btn-success btn-xs" data-power="Cms/editCategory" href="<?= U('Cms/editCategory', array('id' => $val['id'])) ?>">修改栏目</a>
                        <a class="btn btn-danger btn-xs" data-power="Cms/delCategory" href="javascript:void(0)" onclick="deleteColumn(<?= $val['id'] ?>)">删除</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<!--/panel-->


<script>
    //删除栏目
    function deleteColumn(id) {
        layer.confirm('您确定要删除选中的栏目么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U('Cms/delCategory') ?>', {id: id}, function (data) {
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

