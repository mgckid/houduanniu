<?php foreach($form_schema as $value):?>
    <?php if($value['type']=='editor'):?>
        <!--编辑器初始化 开始-->
        <script>
            $(function () {
                ueditor('<?=$value['name']?>');
            })
        </script>
        <!--编辑器初始化 结束-->
    <?php endif;?>
<?php endforeach;?>

<?php
    $names = [];
?>
<?php foreach($form_schema as $value):?>
    <?php
        if ($value['type'] == 'file') {
            $names[]= $value['name'];
        }
    ?>
<?php endforeach;?>
<?php if ($names): ?>
    <!--上传初始化 开始-->
    <script>
        $(function () {
            init_upload(<?=json_encode($names)?>);
        })
    </script>
    <!--上传初始化 结束-->
<?php endif; ?>
