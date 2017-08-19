<header class="header">
    <div class="container">
        <h1 class="logo">
            <a href="/" title="大后端博客首页"> <img src="/static/blog/images/logo.png"/></a>
        </h1>
        <div class="brand">欢迎光临<br>我们一直在努力</div>
        <div class="topbar">
            <ul class="site-nav topmenu">
                <li id="menu-item-112" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-112">
                    <a href="<?=U('Index/tagList')?>">标签云</a>
                </li>
                <li id="menu-item-115" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-115">
                    <a href="###">友情链接</a>
                </li>
            </ul>

        </div>
        <ul class="site-nav site-navbar">
            <li  id="menu-item-0"  class="menu-item menu-item-type-custom menu-item-object-custom  menu-item-home menu-item-0 <?=(strtolower(\houduanniu\base\Application::getController())=='index'&& strtolower(\houduanniu\base\Application::getAction())=='index')?'current-menu-item':''?>"><a href="/"> 首页</a></li>
            <?php foreach ($navList as $value):?>
            <li id="menu-item-<?=$value['id']?>" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-<?=$value['id']?>">
                <a href="<?= $value['cate_type'] == 30 ? $value['jump_url'] : U('Index/category', array('cate' => $value['alias'])) ?>"><?= $value['name'] ?></a>
            </li>
            <?php endforeach;?>
        </ul>
        <i class="fa fa-bars m-icon-nav"></i>
    </div>
</header>