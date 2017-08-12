<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
    <div class="panel-body">
        <!--table-->
        <table class="table">
            <thead>
            <tr>
                <th>内容id</th>
                <th>内容标题</th>
                <th>内容链接</th>
                <th>采集状态</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($itemList as $val): ?>
                <tr id="position<?= $val['id'] ?>">
                    <td><?= $val['id'] ?></td>
                    <td><?= $val['title'] ?></td>
                    <td><?= $val['url'] ?></td>
                    <td><?= $val['collect_status_text'] ?></td>
                    <td><?= $val['created'] ?></td>
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