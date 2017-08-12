<?php $this->layout('Layout/admin') ?>
<style>
    .recommend {
        line-height: 30px;
        height: 30px;
    }

    .recommend span {
        margin-right: 15px;
    }

    .recommend span i {
        font-weight: 600;
        font-style: normal;
    }

    .tagInput input.tag_id {
        position: relative
    }

    .tagInput .tag_box {
        position: absolute;
        top: 10px;
        left: 20px
    }

    .tagInput .tag_box .label {
        margin-right: 5px;
    }
</style>

<div class="panel panel-default">
    <div class="panel-body">
        <div style="max-width: 1230px;">
            <form action="<?= U('Cms/addArticle') ?>" name="addArticle" method="post" class="form form-horizontal">
                <input type="hidden" value="<?= $info['id'] ?>" name="id"/>

                <div class="form-group">
                    <label class="col-sm-2 control-label">文章标题</label>

                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" value="<?= $info['title'] ?>" placeholder="点击输入文章标题">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">文章别名</label>

                    <div class="col-sm-10">
                        <input type="text" name="title_alias" class="form-control" value="<?= $info['title_alias'] ?>" placeholder="点击输入文章别名">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">作者</label>

                    <div class="col-sm-10">
                        <input type="text" name="editor" class="form-control" value="<?= $info['editor'] ?>" placeholder="点击输入作者">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">文章栏目</label>

                    <div class="col-sm-10">
                        <select name="column_id" class="form-control">
                            <option value="0">根目录</option>
                            <?php foreach ($list as $v) { ?>
                                <option <?= $v['id'] == $info['column_id'] ? 'selected="selected"' : '' ?>   value="<?= $v['id'] ?>"><?= $v['html'].$v['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">推荐位设置</label>

                    <div class="col-sm-10 recommend">
                        <span><input type="checkbox" name="is_recommend" value="10" data-value="<?= $info['is_recommend'] ?>"/><i>推荐</i></span>
                        <span><input type="checkbox" name="is_top" value="10" data-value="<?= $info['is_top'] ?>"/><i>头条</i></span>
                        <span><input type="checkbox" name="is_image" value="10" data-value="<?= $info['is_image'] ?>"/><i>图片</i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">文章缩略图</label>

                    <div class="col-sm-10 form-inline">
                        <input type="hidden" value="<?= $info['image_name'] ?>" name="image_name"  />
                        <input type="file" id="thumb_img" data-preview="<?= $info['thumb'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">文章关键字</label>

                    <div class="col-sm-10">
                        <input type="text" name="keyword" class="form-control" value="<?= $info['keyword'] ?>" placeholder="点击输入关键字">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">文章描述</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" name="description" rows="3" placeholder="点击输入描述"><?= $info['description'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">发布时间</label>

                    <div class="col-sm-10">
                        <input type="text" name="public_time" id="dateTimePicker" class="form-control" value="<?= $info['public_time'] ?>" placeholder="点击输入发布时间">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">文章内容</label>

                    <div class="col-sm-10">
                        <textarea name="content" id="content" style="height: 500px;"><?= $info['content'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">标签</label>
                    <div class="col-sm-10 tagInput">
                        <input type="text"   name="tag_name"  class="form-control tag_id" value="<?= !empty($post_tag) ? join(',', array_column($post_tag, 'tag_name')) : '' ?>"    placeholder="请选择文章标签" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button type="submit" data-power="Cms/addArticle" class="btn btn-success">添加</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                        <button type="button" id="fenci" class="btn btn-primary ml10">文章分词</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="tag_list" style="display: none;">
    <div class="padding10">
        <?php foreach ($tag_list as $val): ?>
            <span class="label label-primary" data-id="<?= $val['tag_id'] ?>"><?= $val['tag_name'] ?></span>
        <?php endforeach; ?>
    </div>
</div>
<!--上传 开始-->
<?= $this->insert('Common/plug_upload_fileinput') ?>
<script>
    $(function () {
        fileInput('thumb_img', 'image_name');
    })
</script>
<!--上传 结束-->
<!--编辑器 开始-->
<?= $this->insert('Common/plug_ueditor') ?>
<script>
    $(function () {
        ueditor('content');
    })
</script>
<!--编辑器 结束-->


<!--表单提交 开始-->
<script>
    $(function () {
        $(function () {
            $('form[name=addArticle]').ajaxForm({
                dataType: 'json',
                error: function () {
                    layer.msg('服务器无法连接')
                },
                success: function (data) {
                    layer.alert(data.msg)
                    if (data.status == 1) {
                        // window.location.href = '<?= U('Cms/articleList') ?>';
                    }
                }
            })
        })
    })
</script>
<!--表单提交 结束-->

<!--时间选择 开始-->
<link rel="stylesheet" href="/static/common/dateTimePicker/jquery.datetimepicker.css"/>
<script src="/static/common/dateTimePicker/jquery.datetimepicker.full.js"></script>
<script>
    $('#dateTimePicker').datetimepicker({
        format: 'Y-m-d H:i:s'
    });
</script>
<!--时间选择 结束-->

<!--本页js开始-->
<script>
    //推荐位 checkbox 选中
    $(function () {
        if ($('[name=is_recommend]').data('value') == 10) {
            $('[name=is_recommend]').prop('checked', true);
        }
        if ($('[name=is_top]').data('value') == 10) {
            $('[name=is_top]').prop('checked', true);
        }
        if ($('[name=is_image]').data('value') == 10) {
            $('[name=is_image]').prop('checked', true);
        }
    })


    //分词操作
    $('#fenci').on('click',function(){
        var content = $('[name=content]').val();
        $.post('<?=U('cms/ajaxFenci')?>', {content: content}, function (data) {
            if (data.status != 1) {
                layer.alert(data.msg);
            }
            //关键字
            $('[name=keyword]').val(data.data.keyword);
            //描述
            $('[name=description]').val(data.data.description);
            //标签
            $('[name=tag_name]').val(data.data.tag)
        }, 'json')
    })
</script>
<!--本页js结束-->

