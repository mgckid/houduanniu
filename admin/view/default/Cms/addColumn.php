<?php $this->layout('Layout/admin') ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-10">
            <form name="addColumn" action="<?= U('Cms/addColumn') ?>" method="post"  class="form form-horizontal">
                <input type="hidden" name="id" value="<?= $info['id'] ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">所属栏目</label>
                    <div class="col-sm-10">
                        <select name="pid" class="form-control">
                            <option value="0">根目录</option>
                            <?php foreach ($list as $v) { ?>
                                <option  value="<?= $v['id'] ?>" <?= $v['id'] == $info['pid'] ? 'selected="selected"' : '' ?> ><?= $v['html'].$v['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">栏目名称</label>

                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?= $info['name'] ?>" placeholder="点击输入栏目名称">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">栏目别名</label>

                    <div class="col-sm-10">
                        <input type="text" name="alias" class="form-control" value="<?= $info['alias'] ?>" placeholder="点击输入栏目别名">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">排列顺序</label>

                    <div class="col-sm-10">
                        <input type="text" name="sort" class="form-control" value="100" value="<?= $info['sort'] ?>" placeholder="点击输入排列顺序">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">栏目关键字</label>

                    <div class="col-sm-10">
                        <input type="text" name="keyword" class="form-control" value="<?= $info['keyword'] ?>"  placeholder="点击输入关键字">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">栏目描述</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" name="description" rows="3"  placeholder="点击输入描述"><?= $info['description'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">是否导航显示</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="nav_display"  <?=$info['nav_display']==10?'checked="checked"':''?>  value="10"> 不显示
                            </label>
                            <label>
                                <input type="radio" name="nav_display"   <?=$info['nav_display']==20?'checked="checked"':''?>  value="20"> 显示
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">栏目类型</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label>
                                <input type="radio" name="cate_type"  <?=$info['cate_type']==10?'checked="checked"':''?>  value="10"> 文章列表
                            </label>
                            <label>
                                <input type="radio" name="cate_type"   <?=$info['cate_type']==20?'checked="checked"':''?>  value="20"> 单页
                            </label>
                            <label>
                                <input type="radio" name="cate_type"   <?=$info['cate_type']==30?'checked="checked"':''?>  value="30"> 跳转页
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">跳转页链接</label>

                    <div class="col-sm-10">
                        <input type="text" name="jump_url" class="form-control"   value="<?= $info['jump_url'] ?>" placeholder="点击输入跳转页链接">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">列表页模版</label>

                    <div class="col-sm-10">
                        <input type="text" name="list_template" class="form-control"   value="<?= $info['list_template'] ?>" placeholder="点击输入列表页模版">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">详情页模版</label>

                    <div class="col-sm-10">
                        <input type="text" name="detail_template" class="form-control"   value="<?= $info['detail_template'] ?>" placeholder="点击输入详情页模版">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">单页内容</label>
                    <div class="col-sm-10">
                        <textarea  name="page_content"  id="page_content" style="height: 500px;" ><?= $info['page_content'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button data-power="Cms/addColumn" class="btn btn-success" type="submit">提交</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/panel-->
<!--编辑器 开始-->
<?= $this->insert('Common/plug_ueditor') ?>
<script>
    $(function () {
        ueditor('page_content');
    })
</script>
<!--编辑器 结束-->
<script>
    $(function () {
        $('form[name=addColumn]').ajaxForm({
            dataType: 'json',
            success: function (data) {
                layer.alert(data.msg)
                if (data.status == 1) {
                   // window.location.href = '<?= U('Cms/index') ?>';
                }
            }
        })
    })
</script>
