<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="operate_box">
            <a class="btn btn-sm btn-success" href="<?= U('Cms/addPage') ?>"  data-power="Cms/addPage">添加单页</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>id</th>
                <th>单页名称</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $v) { ?>
                <tr>
                    <td><?= $v['page_id'] ?></td>
                    <td><?= $v['page_name'] ?></td>
                    <td><?= $v['created'] ?></td>
                    <td><?= $v['modified'] ?></td>
                    <td>
                        <a href="<?= U('Cms/addPage', array('id' => $v['page_id'])) ?>"  data-power="Cms/addPage" class="btn btn-xs btn-success">编辑</a>
                        <button class="btn btn-xs btn-danger" data-power="Cms/delPage" name="delpage"  page_id="<?= $v['page_id'] ?>">删除 </button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <!--/列表-->
        <!--/分页-->
    </div>

</div>

<form name="delPageForm" action="<?= U('Cms/delPage') ?>" method="post">
    <input type="hidden" name="page_id"/>
</form>
<script>
    $('[name=delpage]').on('click', function () {
        var accessId = $(this).attr('page_id');
        layer.confirm('确定要删除么？', function () {
            $('[name=delPageForm] [name=page_id]').val(accessId)
            $('form[name="delPageForm"]').ajaxSubmit({
                dataType: 'json',
                success: function (data) {
                    layer.alert(data.msg)
                    if (data.status == 1) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500)
                    }
                },
                error: function () {
                    layer.alert('服务器访问出错');
                }
            });
        }, function () {
            layer.close()
        });
    })
</script>
