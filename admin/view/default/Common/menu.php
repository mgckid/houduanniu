<style type="text/css">
    .sidebar:before{border:0; background-color:transparent;}
    .sidebar .up_move_btn{height:20px; cursor:pointer;background-color: transparent; color:#fff; text-align:left; width:100%; position:absolute;top:90px; left:0px;z-index:10; cursor:pointer;text-indent:10px;}
    .sidebar .down_move_btn{height:20px; cursor:pointer;background-color:transparent; color:#fff;text-align:left; width:100%; position:absolute;bottom:70px; left:0px; z-index:10;cursor:pointer;text-indent:10px;}
    .nav-sidebar{ position: relative; width:100%;}
    .nav-sidebar > li{padding:0px;border-bottom:1px solid #D6D6D6;}
    .nav-sidebar > li > a {padding:0 0 0 20px; outline: none; line-height:50px; z-index:8001; color:#313131; font-size:16px;border-left:5px solid #f0f0f0; background-image:url(../images/menu_bg_03.png); background-position:center right; background-repeat:no-repeat;}
    .nav-sidebar > li > a span{color: #515459;margin-right: 10px;text-align: left;width: 30px;}
    .nav-sidebar > li:hover{background-color: #E4E4E4 !important;}
    .nav-sidebar > li:hover > a{border-bottom:none;border-right:none;color:#333333;border-left:5px solid #F6704D;background-image:url(../images/menu_bg_06.png); background-position:center right; background-repeat:no-repeat;}
    .nav-sidebar > li:hover > a span{ color:#F6704D;}
    .nav-sidebar > li:hover > a:hover{background-color:#E4E4E4!important;}
    .nav > li > a:focus{background-color: #E4E4E4;}
    .nav-sidebar > li.active{background-color: #E4E4E4 !important;}
    .nav-sidebar > li.active > a{border-bottom:none;border-right:none;color:#333333;background-color:#E4E4E4!important;border-left:5px solid #F6704D;background-image:url(../images/menu_bg_06.png); background-position:center right; background-repeat:no-repeat;}
    .nav-sidebar > li.active > a span{ color:#F6704D;}
    .nav-sidebar > li.active > a:hover{background-color:#E4E4E4!important;}

    .nav-sidebar > li > .zhed >  li > a{color:#333333;}
    .navbar-inverse .navbar-nav > li > a{ color:#fff;}
    .navbar-inverse .navbar-toggle{ border-color:#fff;}
    .icon-bar{ background-color:#999;}
    .navbar-inverse .navbar-toggle:hover{ background-color:#09F;}
    .navbar-inverse .navbar-collapse, .navbar-inverse .navbar-form{ border-color:#fff;}
    .navbar > .container .navbar-brand, .navbar > .container-fluid .navbar-brand{ color:#515151; font-size:20px; height:20px; line-height:24px; font-weight:bold; padding:10px 5px 23px 30px;}/*padding:23px 5px 23px 30px;*/
    .navbar-nav > li > a{ padding:14px 15px; }

    .zhed{list-style-type: none;margin-left:55px; line-height: 35px;}
    .zhed li{border-left:1px dotted #ccc;}
    .zhed li.no_line{border-left:none;}
    .zhed li .sidebar-icon{height: 18px;width:30px; border-bottom:1px dotted #ccc;overflow: hidden; }
    .zhed li .sidebar-icon2{height: 18px;width:30px; border-bottom:1px dotted #ccc;border-left:1px dotted #ccc;overflow: hidden; }
    .nav-sidebar .zhed{display:none;}
    .nav-sidebar > li > .zhed > li > a{
        display: block;
        height: 35px;
        overflow: hidden; 
    }
    .nav-sidebar > li > .zhed > li.zhed-active > a{
        color: #F6704D;
        font-weight: bold;
        display: block;
        height: 35px;
        overflow: hidden;
    }
    .nav-sidebar > li.active > a{background-image:url(../images/menu_bg_07.png);}

</style>
<div class="main clearfix">
    <div class="menu">
        <ul class="nav nav-sidebar"> 
            <?php foreach ($menu as $v) { ?>
                <li class="<?= $v['active'] ?>"><a href="javascript:void(0);"> <span class="glyphicon glyphicon-list-alt"></span><?= $v['access_name'] ?></a>
                    <ul class="zhed" <?php echo $v['active'] ? 'style="display: block;"' : '' ?>> 
                        <?php foreach ($v['sub'] as $val) { ?>
                            <li>
                                <div class="sidebar-icon pull-left"></div><a href="<?= $val['url'] ?>"><?= $val['access_name'] ?></a>
                            </li> 
                        <?php } ?>
                    </ul>
                </li> 
            <?php } ?>
        </ul>
    </div>
    <script type="text/javascript">
        $(".menu .nav>li>a").click(function () {
            // $(".nav li").removeClass("active");
            // $(this).parents("li").addClass("active");
            if ($(this).parents('li').hasClass('active')) {
                $(".nav li").removeClass('active');
                $('.zhed').stop().slideUp();
            } else {
                $(".nav li").removeClass('active');
                $(this).parents('li').addClass('active');
                $('.zhed').stop().slideUp();
                $(this).siblings('.zhed').stop().slideDown();
            }
        });
        $('.nav-tabs a').click(function (e) {
            $(this).parent().addClass('active').siblings().removeClass('active');
            if ($(this).attr('data-toggle') == 'tab') {
                $('.tab-content .tab-pane').hide();
                var a = $(this).attr('href');
                $(a).fadeIn();
                return false;
            }
        })
    </script>