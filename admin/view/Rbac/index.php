<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" data-power="Rbac/addUser" href="<?= U('Rbac/addUser') ?>">添加用户</a>
            <a class="btn btn-sm btn-success" data-power="Rbac/addUserRole" href="<?= U('Rbac/addUserRole') ?>">用户分配角色</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <?php foreach ($list_init as $key=> $value):?>
                        <th><?=$value['field_name']?></th>
                    <?php endforeach;?>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $v) { ?>
                    <tr>
                        <?php foreach ($list_init as $key => $value): ?>
                            <td><?=$v[$key]?></td>
                        <?php endforeach;?>
                        <td>
                            <a href="<?= U('Rbac/addUser', array('id' => $v['id'])) ?>" data-power="Rbac/addUser" class="btn btn-xs btn-danger">编辑</a>
                            <a href="<?= U('Rbac/addUserRole', array('id' => $v['id'])) ?>"  data-power="Rbac/addUserRole" class="btn btn-xs btn-success">分配角色</a>
                             <a href="<?= U('Rbac/resetPassword', array('id' => $v['id'])) ?>" data-power="Rbac/resetPassword" class="btn btn-xs btn-primary">重置密码</a>
                            <button name="delUser" data-power="Rbac/delUser" user_id="<?= $v['id'] ?>" class="btn btn-danger btn-xs">删除用户</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!--/列表-->
        <?= $page ?>
        <!--/分页-->
    </div>
</div>
