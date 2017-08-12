<?php $this->layout('Layout/admin') ?>
<style type="text/css">
    /*.btn{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;-o-border-radius:3px;border-radius:3px;*/
        /*background-color: #ff8400;color: #fff;display: inline-block;height: 28px;line-height: 28px;text-align: center;*/
        /*width: 72px;transition: background-color 0.2s linear 0s;border:none;cursor:pointer;margin:0 0 20px;}*/
    /*.demo{width:660px;margin:120px auto}*/
    /*a{cursor: pointer}*/
    /*.btn:hover{background-color: #e95a00;text-decoration: none}*/
    /*ul,li{list-style: none;padding:0;margin:0}*/
    /*.ul_pics{float:left}*/
    /*******图片样式*********/
    .ul_pics li{float:left;width:120px;height:120px;border:1px solid #ddd;margin:0 5px 10px}
    .progress{position:relative;padding: 1px; border-radius:3px; margin:60px 0 0 0;}
    .bar {background-color: green; display:block; width:0%; height:20px; border-radius:3px; }
    .percent{position:absolute; height:20px; display:inline-block;top:3px; left:2%; color:#fff }
    .clearfix:after{visibility:hidden; display:block; font-size:0; content:" "; clear:both; height:0}
    *:first-child+html .clearfix{zoom:1}
    .img_common{width:100%;height: 100%}
</style>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-8">
            <form action="<?= U('Product/addProductImage') ?>" name="add" method="post" class="form form-horizontal">
                <input type="hidden" name="product_id" value="<?=$info['product_id']?>"/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">产品名称</label>

                    <div class="col-sm-10">
                        <span><?=$info['product_name']?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">产品图片</label>

                    <div class="col-sm-10">
                        <ul id="ul_pics" class="ul_pics clearfix">
                            <?php foreach($image_list as $val){?>
                                 <li id="<?=md5($val['image_url'])?>" data-imageid="<?=$val['id']?>"><input type='hidden' name='image_id[]' value='<?=$val['id']?>'/><input type="hidden" name="image_url[]" value="<?=$val['image_url']?>"><img class="img_common" onclick="delPic('<?=$val['image_url']?>','<?=md5($val['image_url'])?>')" src="<?=$val['thumb']?>"></li>
                                 <input type="hidden" value="" name="del_image_id[]"/>
                            <?php }?>
                            <li><img src="/static/admin/default/images/logo.png" id="btn" class="img_common" /></li>
                        </ul>
                    </div>
                </div>
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
<!--上传图片 开始-->
<script type="text/javascript" src="/static/common/plupload.full.min.js"></script>
<script type="text/javascript">
    var uploader = new plupload.Uploader({//创建实例的构造方法
        runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
        browse_button: 'btn', // 上传按钮
        url: "<?=U('Upload/index')?>", //远程上传地址
        flash_swf_url: 'plupload/Moxie.swf', //flash文件地址
        silverlight_xap_url: 'plupload/Moxie.xap', //silverlight文件地址
        filters: {
            max_file_size: '10mb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
            mime_types: [//允许文件上传类型
                {title: "files", extensions: "jpg,png,gif,jpeg"}
            ]
        },
        multi_selection: true, //true:ctrl多文件上传, false 单文件上传
        file_data_name:'Filedata',//指定文件上传时文件域的名称，默认为file,
        init: {
            FilesAdded: function(up, files) { //文件上传前
                if ($("#ul_pics").children("li").length > 5) {
                    layer.msg("您上传的图片太多了！")
                    uploader.destroy();
                } else {
                    var li = '';
                    plupload.each(files, function(file) { //遍历文件
                        li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                    });
                    $("#ul_pics").prepend(li);
                    uploader.start();
                }
            },
            UploadProgress: function(up, file) { //上传中，显示进度条
                var percent = file.percent;
                $("#" + file.id).find('.bar').css({"width": percent + "%"});
                $("#" + file.id).find(".percent").text(percent + "%");
            },
            FileUploaded: function(up, file, info) { //文件上传成功的时候触发
                var result = eval("(" + info.response + ")");//解析返回的json数据
                layer.msg(result.msg);
                var data = result.data;
                var htmls ="<input type='hidden' name='image_id[]' value=''/>"+
                    "<input type='hidden' name='image_url[]' value='" + data.name + "'/>" +
                    "<img class='img_common' onclick=delPic('" + data.name + "','" + file.id + "') src='" + data.thumb + "'/>";
                $("#" + file.id).html(htmls);//追加图片
            },
            Error: function(up, err) { //上传出错的时候触发
                layer.msg(err.message);
            }
        }
    });
    uploader.init();

    function delPic(pic, file_id) { //删除图片 参数1图片路径  参数2 随机数
        if (confirm("确定要删除吗？")) {
            $.post("del.php", {pic: pic}, function(data) {
                var li = $("#" + file_id)
                var imageid =li.data('imageid');
                console.log(li.data());
                li.next('input').val(imageid);
                $("#" + file_id).remove()
            })
        }
    }
</script>
<!--上传图片 结束-->
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