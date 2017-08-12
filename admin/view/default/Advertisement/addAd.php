<?php $this->layout('Layout/admin'); ?>
<div class="panel panel-default">
    <div class="panel-heading"></div>
    <div class="panel-body">
        <form class="form-horizontal" id="addAd" action="<?= U('Advertisement/addAd') ?>" method="post">
            <input type="hidden" name="id" value="<?=$adInfo['id']?>">
            <input type="hidden" id="adWidth" value="">
            <input type="hidden" id="adHeight" value="">
            <div class="form-group">
                <label class="col-sm-2 control-label">所属广告位:</label>
                <div class="col-sm-10">
                    <select name="position_id" class="form-control">
                        <option value="">请选择广告位</option>
                        <?php foreach ($positionList as  $value): ?>
                            <option data-ad_width="<?=$value['ad_width']?>" data-ad_height="<?=$value['ad_height']?>" value="<?= $value['id'] ?>" <?=$adInfo['position_id']==$value['id']?'selected="selected"':''?>><?= $value['position_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告排序:</label>

                <div class="col-sm-10">
                    <input type="text" name="sort" class="form-control" placeholder="广告排序" value="<?=$adInfo['sort']?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告连接:</label>

                <div class="col-sm-10">
                    <input type="text" name="ad_link" class="form-control" placeholder="请输入广告连接" value="<?=$adInfo['ad_link']?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">广告标题:</label>

                <div class="col-sm-10">
                    <input type="text" name="ad_title" class="form-control" placeholder="请输入广告标题" value="<?=$adInfo['ad_title']?>">
                </div>
            </div>
            <div class="form-group" id="ad_image_input">
                <label class="col-sm-2 control-label">上传广告图:</label>

                <div class="col-sm-10">
                    <input type="hidden" name="ad_image" value="<?=$adInfo['ad_image']?>" />
                    <input type="file" id="adImg" data-preview="<?= $adInfo['ad_image_url'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">操作</label>

                <div class="col-sm-10">
                    <button type="submit" data-power="Advertisement/addPosition" class="btn btn-success">添加
                    </button>
                    <button type="reset" class="btn btn-danger ml10">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--上传 开始-->
<?= $this->insert('Common/plug_upload_fileinput') ?>
<script>
    $(function () {
        fileInput('adImg', 'ad_image');
    })
</script>
<!--上传 结束-->
<script>
    $('#addAd').ajaxForm({
        dataType: 'json',
        error: function () {
            layer.msg('服务器无法连接')
        },
        success: function (data) {
            layer.alert(data.msg)
        }
    })
</script>