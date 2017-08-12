<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form action="<?= U('Cms/addPage') ?>" name="add" method="post" class="form form-horizontal">
                <input type="hidden" value="<?= $info['page_id'] ?>" name="page_id"/>
                <!-- Tab panes -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#base" aria-controls="base" role="tab" data-toggle="tab"> 基础信息</a></li>
                    <li role="presentation"><a href="#image" aria-controls="image" role="tab" data-toggle="tab">主图</a></li>
                </ul>
<!--                tab内容 开始-->
                <div class="tab-content mt30">
                    <!--                    基础信息 开始-->
                    <div role="tabpanel" class="tab-pane fade in active" id="base">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">单页名称</label>

                            <div class="col-sm-10">
                                <input type="text" name="page_name" class="form-control" value="<?= $info['page_name'] ?>" placeholder="点击输入单页名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">单页关键字</label>

                            <div class="col-sm-10">
                                <input type="text" name="page_keyword" class="form-control" value="<?= $info['page_keyword'] ?>"  placeholder="点击输入单页关键字">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">单页描述</label>

                            <div class="col-sm-10">
                                <input type="text" name="page_description" class="form-control" value="<?= $info['page_description'] ?>"  placeholder="点击输入单页描述">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">单页内容</label>

                            <div class="col-sm-10">
                                <textarea name="text_content" rows="30" class="form-control height-auto" rows="3"><?=htmlspecialchars_decode( $info['text_content']) ?></textarea>
                            </div>
                        </div>
                    </div>
<!--                    基础信息 结束-->
                    <!--                    主图 开始-->
                    <div role="tabpanel" class="tab-pane fade" id="image">
2
                    </div>
<!--                    主图结束-->
                </div>
                <!--                tab内容 结束-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button type="submit" data-power="Cms/addArticle" class="btn btn-success">添加</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--编辑器 开始-->
<script src="/static/common/kindeditor/kindeditor-all.js"></script>
<script src="/static/common/kindeditor/lang/zh-CN.js"></script>
<script>
    KindEditor.ready(function (K) {
        window.editor = K.create('form textarea[name=text_content]', {
            cssPath: '/static/common/kindeditor/plugins/code/prettify.css',
            uploadJson: '<?= U('Upload/kindeditorUpload') ?>',
            fileManagerJson: '<?= U('Upload/kindeditorFileManage') ?>',
            allowFileManager: true,
            afterBlur: function () {
                this.sync();
            }

        });
    });
</script>
<!--编辑器 结束-->
<!--表单提交 开始-->
<script>
    $(function () {
        $(function () {
            $('form[name=add]').ajaxForm({
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


