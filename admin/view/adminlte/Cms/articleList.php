<?php $this->layout('Layout/admin') ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="operate_box mb10">
<!--                    <a id="selectAll"  class="btn btn-xs btn-primary ">全选</a>-->
<!--                    <a id="rollback" class="btn btn-xs btn-primary ">反选</a>-->
<!--                    <a class="btn btn-danger btn-sm" data-power="Cms/delArticle" href="javascript:void(0)" onclick="delArticles()">批量删除</a>-->
<!--                    <a class="btn btn-success btn-sm" data-power="Cms/addArticle" href="--><?//= U('Cms/addArticle') ?><!--">添加文章</a>-->
                    <!--                    <a class="btn btn-default btn-sm" href="">回收站</a>-->
                </div>
                <table class="table">
                    <tr>
                        <?php foreach ($list_init as $key=> $value):?>
                            <th><?=$value['name']?></th>
                        <?php endforeach;?>
                        <th width="10%">操作</th>
                    </tr>
                    <?php foreach ($list as $v) { ?>
                        <tr id="article<?= $v['id'] ?>">
                            <?php foreach ($list_init as $key=> $value):?>
                                <td><?=$v[$key]?></td>
                            <?php endforeach;?>
                            <td>
                                <a class="btn btn-success btn-xs"  href="<?= U('Cms/addPost', array('id' => $v['id'])) ?>" data-power="Cms/addPost">编辑</a>
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