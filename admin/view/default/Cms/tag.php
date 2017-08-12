<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <a class="btn btn-success btn-sm" data-power="Cms/addTag" href="<?= U('Cms/addTag') ?>">添加标签</a>
    </div>
    <div class="panel-body">
        <!--table-->
        <table class="table">
            <thead>
            <tr>
                <th>标签id</th>
                <th>标签名称</th>
                <th>标签描述</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $val): ?>
                    <tr id="tag<?=$val['tag_id']?>">
                        <th scope="row"><?=$val['tag_id']?></th>
                        <td><?=$val['tag_name']?></td>
                        <td><?=$val['tag_description']?></td>
                        <td><?=$val['created']?></td>
                        <td><?=$val['modified']?></td>
                        <td>
                            <a href="<?=U('Cms/addTag',array('id'=>$val['tag_id']))?>" data-power="Cms/addTag" class="btn btn-success btn-xs">编辑</a>
                            <button class="btn btn-danger btn-xs" onclick="delTag(<?=$val['tag_id']?>)">删除</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?=$pages?>
    </div>
</div>
<script>
    //删除文章
    function delTag(id) {
        if ('number' == typeof (id)) {
            id = [id];
        }
        layer.confirm('您确定要删除选中的标签么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U("Cms/delTag") ?>', {id: id}, function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    for (var i = 0; i < id.length; i++) {
                        $('#tag' + id[i]).remove();
                    }
                }
            }, 'json');
        }, function () {
            return
        });
    }
</script>