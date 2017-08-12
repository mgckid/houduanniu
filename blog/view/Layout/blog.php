<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?= $siteInfo['title']?></title>
    <meta name="keywords" content="<?= $siteInfo['keyword']?>">
    <meta name="description" content="<?= $siteInfo['description']?>">
    <meta name="baidu-site-verification" content="cCKTv2Zpmq" />
    <meta http-equiv="X-UA-Compatible" content="IE=11,IE=10,IE=9,IE=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel='stylesheet' id='_main-css' href='/static/blog/css/main.css' type='text/css' media='all'>
    <link id='_bootstrap-css' href="//cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link id='_fontawesome-css' href="//cdn.bootcss.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!--    <link rel='stylesheet' id='_fontawesome-css' href='/static/blog/css/font-awesome.min.css' type='text/css' media='all'>-->
    <!--    <link rel='stylesheet' id='_bootstrap-css' href='/static/blog/css/bootstrap.min.css' type='text/css' media='all'>-->
    <!--    <script type='text/javascript' src='/static/blog/js/libs/jquery/1.9.1/jquery.min.js'></script>-->
    <!--    <script type='text/javascript' src='/static/blog/js/bootstrap.min.js'></script>-->
    <script src="//cdn.bootcss.com/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.2/js/bootstrap.js"></script>
    <script type='text/javascript' src='/static/blog/js/loader.js'></script>
    <script type='text/javascript' src='/static/blog/js/wp-embed.min.js'></script>
    <!--[if lt IE 9]>
    <script src="/static/blog/js/libs/html5.min.js"></script>
    <![endif]-->
	<script>
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "https://hm.baidu.com/hm.js?898efc58954751f91e409e4bf32c2b45";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	</script>
</head>
<body class="home blog nav_fixed site-layout-2">
<?php $this->insert('Common/header') ?>
<?= $this->section('content'); ?>
<?php $this->insert('Common/footer') ?>
<script>
    window.jsui={
//        www: 'http://demo.themebetter.com/dux',
//        uri: 'http://demo.themebetter.com/dux/wp-content/themes/dux',
        ver: '1.9',
        roll: ["1","2"],
        ajaxpager: '0'
//        url_rp: 'http://demo.themebetter.com/dux/reset-password'
    };

    tbquire.config({
        baseUrl: 'static/blog/js',
        urlArgs: 'ver=1.9',
        paths: {
            'jquery.cookie' : 'libs/jquery.cookie.min',
            'jsrender'      : 'libs/jsrender.min',
            'router'        : 'libs/router.min',
            'lazyload'      : 'libs/lazyload.min',
            'prettyprint'   : 'libs/prettyprint',
            'ias'           : 'libs/ias.min',
            'hammer'        : 'libs/hammer.min',
            'main'          : 'main',
            'comment'       : 'comment',
            'user'          : 'user'
        }
    })
    tbquire(['main'])
</script>
</body>
</html>