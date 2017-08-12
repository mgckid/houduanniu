<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <a class="btn btn-success btn-sm" data-power="Advertisement/addPosition"
           href="<?= U('Advertisement/addPosition') ?>">添加广告位</a>
    </div>
    <div class="panel-body">
        <!--table-->
        <table class="table">
            <thead>
            <tr>
                <th>广告位id</th>
                <th>广告位名称</th>
                <th>广告位key</th>
                <th>广告宽度</th>
                <th>广告高度</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($positionList as $val): ?>
                <tr id="position<?= $val['id'] ?>">
                    <td><?= $val['id'] ?></td>
                    <td><?= $val['position_name'] ?></td>
                    <td><?= $val['position_key'] ?></td>
                    <td><?= $val['ad_width'] ?></td>
                    <td><?= $val['ad_height'] ?></td>
                    <td><?= $val['created'] ?></td>
                    <td><?= $val['modified'] ?></td>
                    <td>
                        <a class="btn btn-success btn-xs" data-power="Advertisement/addPosition"  href="<?= U('Advertisement/addPosition', array('id' => $val['id'])) ?>">编辑</a>
                        <a class="btn btn-danger btn-xs" data-power="Advertisement/delPosition"   href="javascript:void(0)" onclick="delPosition(<?=$val['id']?>)">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!--/列表-->
        <?= $pages ?>
        <!--/分页-->
    </div>
</div>
<script>
    //删除广告位
    function delPosition(id) {
        if ('number' != typeof (id)) {
            //id = [id];
            return false;
        }
        layer.confirm('您确定要删除选中的广告位么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U("Advertisement/delPosition") ?>', {id: id}, function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    $('#position' + id).remove();
                }
            }, 'json');
        }, function () {
            return
        });
    }
</script>
