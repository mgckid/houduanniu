<header class="header">
    <nav class="navbar navbar-expand-lg navbar-shadow navbar-dark bg-primary fixed-top" id="primary-navbar" role="navigation">
        <div class="container">
            <a class="navbar-brand" href="/" title="<?=$siteInfo['site_short_name']?>" rel="home"><?=$siteInfo['site_short_name']?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarNavDropdown" class="collapse navbar-collapse">
                <div class="mr-auto">
                    <ul id="main-nav" class="navbar-nav mr-auto">
                        <li class="nav-item active"><a title="首页" href="/" class="nav-link">首页</a></li>
                        <li class="nav-item"><a title="WordPress" href="##wordpress" class="nav-link">WordPress</a></li>
                        <li class="nav-item"><a title="运营" href="##growth" class="nav-link">运营</a></li>
                        <li class="nav-item"><a title="状态" href="http://blog.houduanniu.com/type/status" class="nav-link">状态</a></li>
                        <li class="nav-item"><a title="链接" href="http://blog.houduanniu.com/type/link" class="nav-link">链接</a></li>
                        <li class="nav-item"><a title="关于" href="http://blog.houduanniu.com/about" class="nav-link">关于</a></li>
                        <li class="nav-item"><a title="留言板" href="http://blog.houduanniu.com/contact" class="nav-link">留言板</a></li>
                        <li class="nav-item dropdown dropdown"><a title="更多" href="#" data-toggle="dropdown" class="nav-link dropdown-toggle" aria-haspopup="true" aria-expanded="false">更多 <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="nav-item"><a title="标签云" href="http://blog.houduanniu.com/tag" class="nav-link">标签云</a></li>
                                <li class="nav-item"><a title="知识管理" href="##knowledge-management" class="nav-link">知识管理</a></li>
                                <li class="nav-item"><a title="日常随笔" href="##essay" class="nav-link">日常随笔</a></li>
                                <li class="nav-item"><a title="没地方可放的" href="##uncategorized" class="nav-link">没地方可放的</a></li>
                                <li class="nav-item"><a title="主题" href="http://blog.houduanniu.com/theme" class="nav-link">主题</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <form class="form-inline" role="search" method="get" id="searchform" action="###">
                    <input class="form-control mr-sm-2" type="text" placeholder="搜索..." aria-label="搜索" name="s">
                </form>
            </div>

        </div>
    </nav>
</header><!-- ./header -->