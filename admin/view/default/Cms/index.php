<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box mb10">
            <a class="btn btn-success btn-sm" data-power="Cms/addColumn" href="<?= U('Cms/addColumn') ?>">添加栏目</a>
        </div>
        <table class="table table-bordered">
            <tr>
                <!--<td width="5%">选择</td>-->
                <td width="5%">ID</td>
                <td  width="50%">栏目名称</td>
                <td width="5%">文档数量</td>
                <td>操作</td>
                <td width="5%">排序</td>
            </tr>
            <?php
            foreach ($list as $k => $v) {
                ?>
                <tr id='row<?= $v['id'] ?>'>
                    <!--<td><input type="checkbox" class="checkbox"/></td>-->
                    <td><?= $v['id'] ?></td>
                    <td><?= $v['html'].$v['name'] ?></td>
                    <td><?= $v['article_count'] ?></td>
                    <td>
                        <a class="btn btn-primary btn-xs" data-power="Cms/articleList" href="<?= U('Cms/articleList', array('id' => $v['id'])) ?>">查看内容</a>
                        <a class="btn btn-success btn-xs" data-power="Cms/addColumn" href="<?= U('Cms/addColumn', array('id' => $v['id'])) ?>">管理栏目</a>
                        <a class="btn btn-success btn-xs" data-power="Cms/addArticle" href="<?= U('Cms/addArticle', array('column_id' => $v['id'])) ?>">添加文章</a>
                        <a class="btn btn-danger btn-xs" data-power="Cms/delColumn" href="javascript:void(0)" onclick="deleteColumn(<?= $v['id'] ?>)">删除</a>
                    </td>
                    <td><input type="text" value="<?= $v['sort'] ?>" class="fa-border"/></td>
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
            $.post('<?= U('Cms/delColumn') ?>', {id: id}, function (data) {
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

