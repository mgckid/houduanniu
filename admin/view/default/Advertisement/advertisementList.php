<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <a class="btn btn-success btn-sm" data-power="Advertisement/addPosition"
           href="<?= U('Advertisement/addad') ?>">添加广告</a>
    </div>
    <div class="panel-body">
        <!--table-->
        <table class="table">
            <thead>
            <tr>
                <th>广告id</th>
                <th>广告标题</th>
                <th>广告链接</th>
                <th>广告排序</th>
                <th>广告位名称</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($adList as $val): ?>
                <tr>
                    <td><?= $val['id'] ?></td>
                    <td><?= $val['ad_title'] ?></td>
                    <td><?= $val['ad_link'] ?></td>
                    <td><?= $val['sort'] ?></td>
                    <td><?= $val['position_name'] ?></td>
                    <td><?= $val['created'] ?></td>
                    <td><?= $val['modified'] ?></td>
                    <td>
                        <a class="btn btn-success btn-xs" data-power="Advertisement/addad"  href="<?= U('Advertisement/addad', array('id' => $val['id'])) ?>">编辑</a>
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
