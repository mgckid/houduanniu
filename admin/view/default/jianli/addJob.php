<?php $this->Layout('Layout/admin') ?>
<link rel="stylesheet" type="text/css" href="/static/common/dateTimePicker/jquery.datetimepicker.css"/>
<script type="text/javascript" src="/static/common/dateTimePicker/jquery.datetimepicker.full.min.js"></script>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form action="<?= U('Operation/addEvent') ?>" name="addUser" class="form form-horizontal" method="post">
                <input name="id" type="hidden" value="<?= $info['id'] ?>" />
                <div class="form-group">
                    <label class="col-sm-2 control-label">事件类型</label>

                    <div class="col-sm-10">
                        <select name="event_id" class="form-control">
                            <option value="">请选择</option>
                            <?php foreach ($event_type as $v) { ?>
                                <option  <?php echo $v['event_id'] == $info['event_id'] ? 'selected="selected"' : '' ?>  value="<?= $v['event_id'] ?>"><?= $v['event_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">事件标题</label>

                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" value="<?= $info['title'] ?>" placeholder="点击输入事件标题">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">事件副标题</label>
                    <div class="col-sm-10">
                        <input type="text" name="sub_title" class="form-control"  value="<?= $info['sub_title'] ?>" placeholder="点击输入事件副标题">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">事件描述</label>

                    <div class="col-sm-10">
                        <textarea  class="form-control" name="description"   placeholder="点击输入事件描述"><?= $info['description'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">开始时间</label>

                    <div class="col-sm-10">
                        <input type="text"  class="form-control" name="begin_time"   placeholder="请选择开始时间" value="<?= $info['begin_time'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">结束时间</label>

                    <div class="col-sm-10">
                        <input type="text"  class="form-control" name="end_time"   placeholder="请选择结束时间" value="<?= $info['end_time'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">排序</label>

                    <div class="col-sm-10">
                        <input type="text"  class="form-control" name="sort"   placeholder="点击输入排序值" value="<?= $info['sort'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button type="submit" data-power="Operation/addFlink" class="btn btn-success ">保存</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){
        //选择时间
        var conf = {
            lang:"ch",           //语言选择中文
            format:"Y-m-d",      //格式化日期
            timepicker:false,    //关闭时间选项
            yearStart:'2000',     //设置最小年份
            yearEnd:'2050',        //设置最大年份
          //  todayButton:false    //关闭选择今天按钮
        };
       $("[name=begin_time]").datetimepicker(conf);
       $("[name=end_time]").datetimepicker(conf);
    })
    //提交表单
    $('form[name=addUser]').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器无法连接');
        },
        success: function (data) {
            layer.alert(data.msg);
        }
    });
</script>