<?php $this->layout('Layout/admin') ?>


<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form action="<?= U('Product/addProduct') ?>" name="add" method="post" class="form form-horizontal">
                <input type="hidden" name="product_id" value="<?=$info['product_id']?>"/>
<!--                <input type="hidden" name="image_url" value="--><?//= $info['image_url'] ?><!--" />-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">产品名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="product_name" class="form-control" value="<?=$info['product_name']?>" placeholder="点击输入产品名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">产品类别</label>

                    <div class="col-sm-10">
                        <select name="category_id" class="form-control">
                            <option value="">选择分类</option>
                            <?php foreach ($category as $v) { ?>
                                <option value="<?= $v['id'] ?>"  <?php echo $v['id'] == $info['category_id'] ? 'selected="selected"' : '' ?>><?= $v['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">产品描述</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" name="product_intro" rows="3" placeholder="点击输入描述"><?=$info['product_intro']?></textarea>
                    </div>
                </div>
<!--                <div class="form-group">-->
<!--                    <label class="col-sm-2 control-label">产品图片</label>-->
<!---->
<!--                    <div class="col-sm-10 form-inline">-->
<!--                        <a id="choiceImg" class="btn btn-success btn-sm">选择</a>-->
<!--                        <a id="uploadImg" class="btn btn-success btn-sm">上传图片</a>-->
<!--                        <a id="check_img_btn" class="btn btn-primary  btn-sm  --><?php //if (!$info['image_url']) {  echo("hide");  } ?><!--" data-toggle="modal" data-target="#check_img"> 查看主图 </a>-->
<!--                    </div>-->
<!--                </div>-->

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
<!--弹出层 开始-->
<div class="modal fade bs-example-modal-sm" id="check_img" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 400px;">
            <div class="modal-body">
                <div class="check_image">
                    <img src="<?= $info['thumb'] ?>" alt=""/>
                </div>
            </div>
        </div>
    </div>
</div>
<!--弹出层 结束-->
<!--上传缩略图 开始-->
<form name="uploadForm" class="hide" action="<?= U('Upload/index') ?>" method="post"
      enctype="multipart/form-data">
    <input name="Filedata" type="file"/>
</form>
<script type="text/javascript">
    $('#choiceImg').on('click', function () {
        $('[name=uploadForm] [type=file]').click();
        console.log($('[name=uploadForm] [type=file]'));
    })
    $('#uploadImg').on('click', function () {
        $('[name=uploadForm]').ajaxSubmit({
            dataType: 'json',
            error: function () {
                layer.msg('服务器连接错误')
            },
            success: function (data) {
                layer.alert(data.msg);
                if (data.status == 1) {
                    $('input[name=image_url]').val(data.data.name)
                    $('.check_image').find('img').attr('src', data.data.thumb);
                    $('#check_img_btn').removeClass('hide');
                }
            }
        })
    })
</script>
<!--上传缩略图 结束-->
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