<?php $this->layout('Layout/admin'); ?>
<script type="text/javascript" src="/static/admin/js/plupload-master/js/plupload.full.min.js"></script>
<style>
    .container-box{width: 150px;height: 150px;background: #EFEFEF;overflow: hidden}
    .container-box .add{font-size: 80px; color: #CCCCCC;width: 100%;text-align: center;line-height: 150px;}
    .container-box .preview{}
</style>

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
                                <div class="container-box">
                                    <div class="add" id="choice_file">+</div>
                                </div>
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
    var browse_button = 'choice_file';
    var config = {
        browse_button: browse_button, // this can be an id of a DOM element or the DOM element itself
        url: '<?=U('Upload/index')?>',
        multipart: true,//为true时将以multipart/form-data的形式来上传文件，为false时则以二进制的格式来上传文件。
        multipart_params: {source: 'upload'},//上传时的附加参数，
        max_retries: 0, //	当发生plupload.HTTP_ERROR错误时的重试次数，为0时表示不重试
        chunk_size: 0,   //	分片上传文件时，每片文件被切割成的大小，为数字时单位为字节。也可以使用一个带单位的字符串，如"200kb"。当该值为0时表示不使用分片上传功能
        multi_selection: false, //	是否可以在文件浏览对话框中选择多个文件，true为可以，false为不可以。默认true，
        unique_names: false,//当值为true时会为每个上传的文件生成一个唯一的文件名，
        file_data_name: 'file',//指定文件上传时文件域的名称，默认为file,例如在php中你可以使用$_FILES['file']来获取上传的文件信息
        /*不需要用到的属性 开始*/
        /*flash_swf_url: '/static/admin/js/plupload-master/js/Moxie.swf',//flash上传组件的url地址
        silverlight_xap_url: '/static/admin/js/plupload-master/js/Moxie.xap'//silverlight上传组件的url地址
        filters: {
            prevent_duplicates: true, //不允许选取重复文件
            mime_types: [ //只允许上传图片和zip文件
                {title: "Image files", extensions: "jpg,gif,png,jpeg,bmp"},
                {title: "Zip files", extensions: "zip"}
            ],
            max_file_size: '5000kb' //最大只能上传400kb的文件
        },
        resize: {},
        drop_element: '',
        required_features: '',
        container: '',//用来指定Plupload所创建的html结构的父容器，默认为前面指定的browse_button的父元素。*/
        /*不需要用到的属性 结束*/

        /*事件处理开始*/
        init: {
            //当Init事件发生后触发
            PostInit: function (uploader) {
                $('#upload-btn').click(function () {
                    uploader.start();
                })
            },
            //选择文件后触发
            FilesAdded: function (uploader, files) {
                console.log(files);
                var file_name = files[0].name; //文件名
                if (!files[0] || !/image\//.test(files[0].type)) return; //确保文件是图片
                $('#' + browse_button).hide(0)
                var containerObj = $('#' + browse_button).parent('.container-box');
                var preview_id = 'preview_' + files[0].id;
                var progress_id = 'progress_' + files[0].id;
                containerObj.append('<div id="' + preview_id + '" class="preview"></div>')
                containerObj.append('<div id="' + progress_id + '" class="progress"></div>')
                previewImage(files[0], function (image_source) {
                    $('#' + preview_id).append('<image src="' + image_source + '"/>')
                })
            },
            //当文件从上传队列移除后触发
            FilesRemoved: function (uploader, files) {

            },
            //当队列中的某一个文件上传完成后触发
            FileUploaded: function (uploader, file, responseObject) {

            },
            //当上传队列中所有文件都上传完成后触发
            UploadComplete: function (uploader, files) {

            },
            //当发生错误时触发
            Error: function (uploader, errObject) {

            }

        }
        /*事件处理结束*/

    };
    var uploader = new plupload.Uploader(config);
    uploader.init();

    
    //plupload中为我们提供了mOxie对象
    //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
    //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
    function previewImage(file,callback){//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
        if(file.type=='image/gif'){//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
            var fr = new mOxie.FileReader();
            fr.onload = function(){
                callback(fr.result);
                fr.destroy();
                fr = null;
            }
            fr.readAsDataURL(file.getSource());
        }else{
            var preloader = new mOxie.Image();
            preloader.onload = function() {
                preloader.downsize( 300, 300 );//先压缩一下要预览的图片,宽300，高300
                var imgsrc = preloader.type=='image/jpeg' ? preloader.getAsDataURL('image/jpeg',80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                callback && callback(imgsrc); //callback传入的参数为预览图片的url
                preloader.destroy();
                preloader = null;
            };
            preloader.load( file.getSource() );
        }
    }

/*
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
*/

/*    uploader.bind('Error', function (up, err) {
        document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
    });*/
    //上传按钮
//    $('#upload-btn').click(function(){
//        uploader.start();
//    });
</script>



