<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title><?=$siteInfo['title']?></title>
    <meta name="keywords" content="<?=$siteInfo['keyword']?>">
    <meta name="description" content="<?=$siteInfo['description']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        .pagination li.active a{
            z-index: 2;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .pagination li a{
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #ddd;
        }
    </style>
    <link rel='stylesheet' id='lean-toolkit-css'  href='/static/writing/css/toolkit.css' type='text/css' media='all' />
    <link rel='stylesheet' id='lean-font-awesome-css'  href='/static/writing/css/font-awesome.min.css' type='text/css' media='all' />
    <style type="text/css" id="custom-background-css">
        body.custom-background { background-color: #f8f9fa; }
    </style>
    <link rel="icon" href="http://assets.qingzhuti.com/wp-content/uploads/2017/07/cropped-fx-32x32.png" sizes="32x32" />
    <link rel="icon" href="http://assets.qingzhuti.com/wp-content/uploads/2017/07/cropped-fx-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="http://assets.qingzhuti.com/wp-content/uploads/2017/07/cropped-fx-180x180.png" />
    <meta name="msapplication-TileImage" content="http://assets.qingzhuti.com/wp-content/uploads/2017/07/cropped-fx-270x270.png" />
</head>
<body class="home blog custom-background" >
    <?=$this->insert('Common/nav')?>
    <!--主体内容 开始-->
    <?= $this->section('content') ?>
    <!--主体内容 结束-->
<!-- 复用的底部 -->
<footer class="footer mt-3">
    <div class="container">
        <p class="copyright mb-0">&copy; 2012-2017 <a href="http://blog.houduanniu.com/" title="后端牛">后端牛</a>
            .<a href="###" rel="external nofollow" target="_blank">京ICP备13034327号-1</a>
            .Design by <a href="http://blog.houduanniu.com/" target="_blank">qingzhuti.com</a>.</p>
    </div>
</footer>

<script type='text/javascript' src='/static/writing/js/jquery.js'></script>
<script type='text/javascript' src='/static/writing/js/popper.js'></script>
<script type='text/javascript' src='/static/writing/js/tether.js'></script>
<script type='text/javascript' src='/static/writing/js/bootstrap.min.js'></script>
</body>
</html>
