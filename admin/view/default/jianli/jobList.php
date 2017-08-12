<?php $this->layout('Layout/admin'); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" data-power="Operation/addEvent" href="<?= U('Operation/addEvent') ?>">添加事件</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>标题</th>
                    <th>副标题</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                    <th>排序</th>
                    <th>创建时间</th>
                    <th>修改时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $v) { ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= $v['title'] ?></td>
                        <td><?= $v['sub_title'] ?></td>
                        <td><?= $v['begin_time'] ?></td>
                        <td><?= $v['end_time'] ?></td>
                        <td><?= $v['sort'] ?></td>
                        <td><?= $v['created'] ?></td>
                        <td><?= $v['modified'] ?></td>
                        <td>
                             <button data-power="Jianli/delJob" name="delEvent" id="<?= $v['id'] ?>" class="btn btn-xs btn-danger">删除</button>
                            <a href="<?= U('Jianli/addJob', array('id' => $v['id'])) ?>" data-power="Jianli/addJob" class="btn btn-xs btn-success">编辑</a>
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
<!--删除记录 开始-->
<form name="delEventForm" action="<?= U('Operation/delEvent') ?>" method="post">
    <input type="hidden" value="" name="id" />
</form>
<script>
    $('[name=delEvent]').on('click', function () {
        var id = $(this).attr('id');
        layer.confirm('确定要删除么?', function (index) {
            doDel(id);
            layer.close(index);
        });
        function doDel(id) {
            $('[name=delEventForm] [name=id]').val(id);
            $('form[name="delEventForm"]').ajaxSubmit({
                dataType: 'json',
                success: function (data) {
                    layer.alert(data.msg)
                    if (data.status == 1) {
                        window.location.reload();
                    }
                },
                error: function () {
                    layer.alert('服务器访问出错');
                }
            });
        }
    });
</script>
<!--删除记录 结束-->