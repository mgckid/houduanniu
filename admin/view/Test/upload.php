<?php $this->layout('Layout/admin'); ?>
<script type="text/javascript" src="/static/admin/js/plupload-master/js/plupload.full.min.js"></script>


<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form action="" name="autoform" id="autoform" method="post" class="form form-horizontal"><input
                    type="hidden" name="id" id="id"/>

                <div class="form-group">
                    <label class="control-label col-sm-2"> 上传广告图</label>

                    <div class="col-sm-8">
                        <div class="btn-wraper">
                            <div id="container">
                                <a href="#" class="btn btn-success" id="browse" >选择文件...</a>
                                <a href="#" class="btn btn-info" id="upload-btn" >开始上传</a>
                            </div>
                            <ul id="file-list"></ul>
                            <!--<pre id="console"></pre>-->

                        </div>
                    </div>
                    <label class="col-sm-2"> </label>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">操作</label>

                    <div class="col-sm-10">
                        <button class="btn btn-success " data-power="System/addConfig">保存</button>
                        <button type="reset" class="btn btn-danger ml10">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var uploader = new plupload.Uploader({
        browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
        url: '<?=U('Upload/index')?>',
        multipart: true,//为true时将以multipart/form-data的形式来上传文件，为false时则以二进制的格式来上传文件。
        multipart_params: {source: 'upload'},//上传时的附加参数，
        max_retries: 0, //	当发生plupload.HTTP_ERROR错误时的重试次数，为0时表示不重试
        chunk_size: 0,   //	分片上传文件时，每片文件被切割成的大小，为数字时单位为字节。也可以使用一个带单位的字符串，如"200kb"。当该值为0时表示不使用分片上传功能
        multi_selection: true, //	是否可以在文件浏览对话框中选择多个文件，true为可以，false为不可以。默认true，
        unique_names: false,//当值为true时会为每个上传的文件生成一个唯一的文件名，
        file_data_name: 'file'//指定文件上传时文件域的名称，默认为file,例如在php中你可以使用$_FILES['file']来获取上传的文件信息
//        filters: {
//            prevent_duplicates: true, //不允许选取重复文件
//            mime_types: [ //只允许上传图片和zip文件
//                {title: "Image files", extensions: "jpg,gif,png,jpeg,bmp"},
//                {title: "Zip files", extensions: "zip"}
//            ],
//            max_file_size: '5000kb' //最大只能上传400kb的文件
//        },
//        flash_swf_url: '/static/admin/js/plupload-master/js/Moxie.swf',//flash上传组件的url地址
//        silverlight_xap_url: '/static/admin/js/plupload-master/js/Moxie.xap'//silverlight上传组件的url地址
//        resize: {},
//        drop_element: '',
//        required_features: '',
//        container: ''//用来指定Plupload所创建的html结构的父容器，默认为前面指定的browse_button的父元素。

    });
    uploader.init();


    //绑定文件添加进队列事件
    uploader.bind('FilesAdded',function(uploader,files){
        for(var i = 0, len = files.length; i<len; i++){
            var file_name = files[i].name; //文件名
            //构造html来更新UI
            var html = '<li id="file-' + files[i].id +'"><p class="file-name">' + file_name + '</p><p class="progress"></p></li>';
            $(html).appendTo('#file-list');
        }
    });

    //绑定文件上传进度事件
    uploader.bind('UploadProgress',function(uploader,file){
        $('#file-'+file.id+' .progress').css('width',file.percent + '%');//控制进度条
    });

/*    uploader.bind('Error', function (up, err) {
        document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
    });*/
    //上传按钮
    $('#upload-btn').click(function(){
        uploader.start();
    });
</script>



