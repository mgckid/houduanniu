<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading"></div>
    <div class="panel-body">
        <form class="form-horizontal" id="addPosition" action="<?=U('Collect/addRule')?>" method="post">
            <input type="hidden" name="rule_id" value="<?=$ruleInfo['rule_id']?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">规则名称:</label>
                <div class="col-sm-10">
                    <input type="text" name="rule_name" class="form-control" value="<?=$ruleInfo['rule_name']?>"  placeholder="请输入规则名称">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">规则描述:</label>
                <div class="col-sm-10">
                    <textarea name="rule_description" class="form-control"  rows="3" placeholder="请输入规则描述"><?=$ruleInfo['rule_description']?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">站点域名:</label>
                <div class="col-sm-10">
                    <input type="text" name="site_url" class="form-control" value="<?=$ruleInfo['site_url']?>"  placeholder="请输入站点域名">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">最新列表页链接:</label>
                <div class="col-sm-10">
                    <input type="text" name="latest_list_url" class="form-control" value="<?=$ruleInfo['latest_list_url']?>"  placeholder="请输入最新列表页链接">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">列表页分页格式:</label>
                <div class="col-sm-10">
                    <input type="text" name="list_url_format" class="form-control" value="<?=$ruleInfo['list_url_format']?>"  placeholder="请输入列表页分页格式">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">最大采集页数:</label>
                <div class="col-sm-10">
                    <input type="text" name="max_page_num" class="form-control" value="<?=$ruleInfo['max_page_num']?>"  placeholder="请输入最大采集页数">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">列表页采集规则:</label>
                <div class="col-sm-10">
                    <textarea name="list_rule" class="form-control"  rows="5" placeholder="请输入列表页采集规则"><?=$ruleInfo['list_rule']?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">详情页采集规则:</label>
                <div class="col-sm-10">
                    <textarea name="detail_rule" class="form-control"  rows="10" placeholder="请输入详情页采集规则"><?=$ruleInfo['detail_rule']?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">采集内容字符编码:</label>
                <div class="col-sm-10">
                    <input type="text" name="input_encode" class="form-control" value="<?=$ruleInfo['input_encode']?>"  placeholder="请输入采集内容字符编码">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">本站字符编码:</label>
                <div class="col-sm-10">
                    <input type="text" name="output_encode" class="form-control" value="<?=$ruleInfo['output_encode']?>"  placeholder="请输入本站字符编码">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">操作</label>
                <div class="col-sm-10">
                    <button type="button" id="testListCollect" class="btn btn-primary ml10">列表采集测试</button>
                    <button type="button" id="testDetailCollect" class="btn btn-primary ml10">详情采集测试</button>
                    <button type="submit" class="btn btn-success">添加</button>
                    <button type="reset" class="btn btn-danger ml10">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#addPosition').ajaxForm({
        dataType:'json',
        error: function () {
            layer.msg('服务器无法连接')
        },
        success: function (data) {
            layer.alert(data.msg)
        }
    })
    $("#testListCollect").on('click',function () {
        var rule_name = $('#addPosition [name=rule_name]').val();
        var site_url = $('#addPosition [name=site_url]').val();
        var latest_list_url = $('#addPosition [name=latest_list_url]').val();
        var list_rule = $('#addPosition [name=list_rule]').val();
        var input_encode = $('#addPosition [name=input_encode]').val();
        var output_encode = $('#addPosition [name=output_encode]').val();
        var data = {
            rule_name: rule_name,
            site_url: site_url,
            latest_list_url: latest_list_url,
            list_rule: list_rule,
            input_encode:input_encode,
            output_encode:output_encode,
        };
        $.post('<?=U('Collect/collectTest',array('test_type'=>'list'))?>',data,function (data) {
            layer.open({
                type: 2,
                title: '采集规则测试',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['893px', '600px'],
                content: '<?=U('Collect/collectTest',array('test_type'=>'list'))?>'
            });
        },'json')
    })

    $("#testDetailCollect").on('click',function () {
        var rule_name = $('#addPosition [name=rule_name]').val();
        var site_url = $('#addPosition [name=site_url]').val();
        var latest_list_url = $('#addPosition [name=latest_list_url]').val();
        var list_rule = $('#addPosition [name=list_rule]').val();
        var input_encode = $('#addPosition [name=input_encode]').val();
        var output_encode = $('#addPosition [name=output_encode]').val();
        var detail_rule = $('#addPosition [name=detail_rule]').val();
        var data = {
            rule_name: rule_name,
            site_url: site_url,
            latest_list_url: latest_list_url,
            list_rule: list_rule,
            input_encode: input_encode,
            output_encode: output_encode,
            detail_rule: detail_rule
        };
        console.log(data);
        $.post('<?=U('Collect/collectTest',array('test_type'=>'detail'))?>',data,function (data) {
            layer.open({
                type: 2,
                title: '采集规则测试',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['893px', '600px'],
                content: '<?=U('Collect/collectTest',array('test_type'=>'detail'))?>'
            });
        },'json')
    })
</script>