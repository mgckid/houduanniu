<?php $this->layout('Layout/admin'); ?>
<link href="/static/admin/js/jQuery-File-Upload-9.19.1/css/jquery.fileupload.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/static/admin/js/jQuery-File-Upload-9.19.1/js/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/static/admin/js/jQuery-File-Upload-9.19.1/js/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="/static/admin/js/jQuery-File-Upload-9.19.1/js/jquery.fileupload.js"></script>


<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form action="" name="autoform" id="autoform" method="post" class="form form-horizontal"><input
                    type="hidden" name="id" id="id"/>

                <div class="form-group">
                    <label class="control-label col-sm-2"> 上传广告图</label>

                    <div class="col-sm-8">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>Select files...</span>
                            <!-- The file input field used as target for the file upload widget -->
                            <input id="fileupload" type="file" name="files[]" multiple>
                        </span>
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
    $(function () {
/*        {
            url: url,
                dataType: 'json',
            done: function (e, data) {
            console.log(data);
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#files');
            });
        },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        }*/
        var url = '<?=U('upload/index')?>';
        var config = {
            url: url,
            dataType: 'json',
            add:function(e, data){
                console.log(data);
            },
            progress: function(e, data) {
                console.log(data);
                // Track Progress
            },
            success: function(response, status) {
                console.log(response,status);
                // Success callback
            },
            error: function(error) {
                console.log(error);
                // Error callback
            }
        };
        $('#fileupload').fileupload(config).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
</script>



