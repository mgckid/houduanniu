<?php $this->layout('Layout/admin') ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="operate_box mb10">
                    <a id="selectAll"  class="btn btn-xs btn-primary ">全选</a>
                    <a id="rollback" class="btn btn-xs btn-primary ">反选</a>
                    <a class="btn btn-danger btn-sm" data-power="Cms/delArticle" href="javascript:void(0)" onclick="delArticles()">批量删除</a>
                    <a class="btn btn-success btn-sm" data-power="Cms/addArticle" href="<?= U('Cms/addArticle') ?>">添加文章</a>
                    <!--                    <a class="btn btn-default btn-sm" href="">回收站</a>-->
                </div>
                <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <th width="5%">
                            选择
                        </th>
                        <th width="5%">ID</th>
                        <th>文章名称</th>
                        <th width="10%">所在栏目</th>
                        <th width="10%">是否推荐</th>
                        <th width="10%">是否头条</th>
                        <th width="10%">是否图片</th>
                        <th width="10%">发布时间</th>
                        <th width="10%">创建时间</th>
                        <th width="10%">修改时间</th>
                        <th width="10%">操作</th>
                    </tr>
                    <?php foreach ($list as $v) { ?>
                        <tr id="article<?= $v['id'] ?>">
                            <td><input type="checkbox" name="articleID" class="checkbox" value="<?= $v['id'] ?>"/></td>
                            <td><?= $v['id'] ?></td>
                            <td><?= $v['title'] ?></td>
                            <td><?= $v['column_name'] ?></td>
                            <td><?= $v['is_recommend_text'] ?></td>
                            <td><?= $v['is_top_text'] ?></td>
                            <td><?= $v['is_image_text'] ?></td>
                            <td><?= $v['public_time'] ?></td>
                            <td><?= $v['created'] ?></td>
                            <td><?= $v['modified'] ?></td>
                            <td>
                                <a class="btn btn-success btn-xs"  href="<?= U('Cms/addArticle', array('id' => $v['id'])) ?>" data-power="Cms/addArticle">编辑</a>
                                <a class="btn btn-danger ml10 btn-xs" href="javascript:void(0)" onclick="delArticle(<?= $v['id'] ?>)" data-power="Cms/delArticle">删除</a>
                            </td>
                        </tr>
                    <?php } ?>


                </table>
                <!--/列表-->
                <?= $page ?>
                <!--/分页-->
            </div>
        </div>
<script>
    //删除文章
    function delArticle(id) {
        if ('number' == typeof (id)) {
            id = [id];
        }
        layer.confirm('您确定要删除选中的文章么？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post('<?= U("Cms/delArticle") ?>', {id: id}, function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                    for (var i = 0; i < id.length; i++) {
                        $('#article' + id[i]).remove();
                    }
                }
            }, 'json');
        }, function () {
            return
        });
    }
    /**
     * 批量删除文章
     * @returns {undefined}
     */
    function delArticles() {
        var id = []
        $('table input:checkbox').each(function () {
            var isChecked = $(this).is(function () {
                return $(this).prop('checked');
            });
            if (isChecked) {
                id.push($(this).val());
            }
        });
        if (id.length != 0) {
            delArticle(id);
        } else {
            layer.alert('请选择要删除的文章')
        }
    }

    //全选
    $('#selectAll').on('click', function () {
        var isChecked = $(this).is(function () {
            return $(this).attr('checked');
        });
        if (!isChecked) {
            $(this).attr('checked', 'checked');
            $('table input:checkbox').prop('checked', true);
        } else {
            $(this).removeAttr('checked');
            $('table input:checkbox').prop('checked', false);
        }
    });

    //反选
    $('#rollback').on('click', function () {
        $('#selectAll').removeAttr('checked');
        $('table input:checkbox').each(function () {
            var isChecked = $(this).is(function () {
                return $(this).prop('checked');
            });
            if (!isChecked) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
    });




</script>