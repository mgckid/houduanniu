<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading">

    </div>
    <div class="panel-body">
        <!--table-->
        <table class="table">
            <thead>
            <tr>
                <th>规则id</th>
                <th>规则名称</th>
                <th>规则描述</th>
                <th>最新列表页链接</th>
                <th>最大采集页数</th>
                <th>创建时间</th>
                <th>修过时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($itemList as $val): ?>
                <tr id="position<?= $val['rule_id'] ?>">
                    <td><?= $val['rule_id'] ?></td>
                    <td><?= $val['rule_name'] ?></td>
                    <td><?= $val['rule_description'] ?></td>
                    <td><?= $val['latest_list_url'] ?></td>
                    <td><?= $val['max_page_num'] ?></td>
                    <td><?= $val['created'] ?></td>
                    <td><?= $val['modified'] ?></td>
                    <td>
                        <a class="btn btn-success btn-xs"    href="<?= U('Collect/addRule', array('id' => $val['rule_id'])) ?>">编辑</a>
                        <a class="btn btn-danger btn-xs"   href="javascript:void(0)" onclick="delPosition(<?=$val['rule_id']?>)">删除</a>
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