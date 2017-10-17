<?php $this->layout('Layout/admin'); ?>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="/static/admin/js/jQuery-File-Upload-9.19.1/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/static/admin/js/jQuery-File-Upload-9.19.1/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/static/admin/js/jQuery-File-Upload-9.19.1/js/jquery.fileupload.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->

<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12">
            <form action="" name="autoform" id="autoform" method="post" class="form form-horizontal"><input
                    type="hidden" name="id" id="id"/>

                <div class="form-group">
                    <label class="control-label col-sm-2"> 上传广告图</label>

                    <div class="col-sm-8">
                        <input type="hidden" name="ad_image" id="ad_image"/>
                        <input id="fileupload" type="file" name="files[]" multiple="">
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
  //  $('#upload_file').fileupload();
  $(function () {
      // Change this to the location of your server-side upload handler:
      var config = {
          url: '<?=U('Upload/index')?>',
          dataType: 'json',
          done: function (e, data) {
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
      };
      $('#fileupload').fileupload(config);
  });
</script>



