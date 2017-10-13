<?php foreach($form_schema as $value):?>
    <?php if($value['type']=='file'):?>
        <!--上传初始化 开始-->
        <script>
            $(function () {
                fileInput('upload_file');
            })
        </script>
        <!--上传初始化 结束-->
    <?php endif;?>
    <?php if($value['type']=='editor'):?>
        <!--编辑器初始化 开始-->
        <script>
            $(function () {
                fileInput('<?=$value['name']?>');
            })
        </script>
        <!--编辑器初始化 结束-->
    <?php endif;?>
<?php endforeach;?>