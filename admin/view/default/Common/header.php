<div class="header bg-ruanpower clearfix">
    <div class="logo">
        <?=$siteInfo['short_site_name']?>后台管理系统
    </div>
    <div class="info clearfix">
        <div class="welcome">
            <i class="fa fa-user fa-lg"></i>&nbsp;您好：&nbsp;<?=$loginInfo['true_name']?>
        </div>
        <ul class="clearfix">
            <li><i class="fa fa-sign-out fa-lg"></i>&nbsp;<a href="javascript:void(0)" onclick="logout()">注销</a></li>
            <li data-power="Rbac/resetPassword" style="display: none"><i class="fa fa-edit fa-lg"></i>&nbsp;<a href="<?=U('Rbac/resetPassword',array('id'=>$loginInfo['id']))?>">修改密码</a></li>
            <li><i class="fa fa-chrome fa-lg"></i>&nbsp;<a href="<?=C('HOME_URL')?>" target="_blank">预览主页</a></li>
        </ul>
    </div>
</div>