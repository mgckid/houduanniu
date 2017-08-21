<?php $this->layout('Layout/detail')?>
<main class="site-content">

    <div class="container mt-4">
        <div class="site-main">
            <div class="row">
                <div class="col-lg-8">
                    <main class="main-content">


                        <div class="card card-shadow">
                            <div class="card-body">


                                <h1 class="card-title mb-4"><?=$info['title']?></h1>
                                <p class="card-text text-link-color-muted">
                                    <small>
                                        <span class="post-time"><?=date('Y年m月d日',strtotime($info['created']))?></span>
                                        <span class="post-category"> &nbsp;&bull;&nbsp;<a href="http://qingzhuti.com/category/wordpress" rel="category tag"><?=$info['category_name']?></a></span>
                                    </small>
                                </p>

                                <div class="entry-content pt-3">

                                    <?=htmlspecialchars_decode($info['content'])?>

                                    <div class="post-tags mt-4 mb-3"><a href="http://qingzhuti.com/tag/wordpress%e4%b8%bb%e9%a2%98%e5%bc%80%e5%8f%91%e6%95%99%e7%a8%8b" class="btn btn-light btn-sm mr-2 mb-2">WordPress主题开发教程</a></div>
                                </div>

                            </div>
                        </div>

                        <nav class="post-navigation card" role="navigation">
                            <div class="card-body">
                                <h4 class="sr-only sr-only-focusable">Post navigation</h4>
                                <div class="nav-links clearfix">
                                    <div class="nav-previous float-left">&larr; <a href="http://qingzhuti.com/523" rel="prev">上一篇文章</a></div>
                                    <div class="nav-next float-right"><a href="http://qingzhuti.com/378" rel="next">开始创建主题：主样式表（style.css）</a> &rarr;</div>
                                </div><!-- .nav-links -->

                            </div>
                        </nav><!-- .navigation -->

                        <div class="related-posts card">
                            <div class="card-body"><h3 class="card-title h6 mb-3">你可能喜欢：</h3>

                                <div class="row">

                                    <div class="col-md-4 col-6">
                                        <div class="card border-0">
                                            <a class="entry-img" href="http://qingzhuti.com/554">
                                                <img src="http://assets.qingzhuti.com/wp-content/themes/writing/assets/img/placeholder.png" alt="图片占位符" class="card-img rounded-0">
                                            </a>
                                            <div class="card-body px-0 py-3">
                                                <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                                                    <a href="http://qingzhuti.com/554" rel="bookmark">内容类型（Post  Type）与文章形式（Post Format）</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <div class="card border-0">
                                            <a class="entry-img" href="http://qingzhuti.com/554">
                                                <img src="http://assets.qingzhuti.com/wp-content/themes/writing/assets/img/placeholder.png" alt="图片占位符" class="card-img rounded-0">
                                            </a>
                                            <div class="card-body px-0 py-3">
                                                <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                                                    <a href="http://qingzhuti.com/554" rel="bookmark">内容类型（Post  Type）与文章形式（Post Format）</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <div class="card border-0">
                                            <a class="entry-img" href="http://qingzhuti.com/554">
                                                <img src="http://assets.qingzhuti.com/wp-content/themes/writing/assets/img/placeholder.png" alt="图片占位符" class="card-img rounded-0">
                                            </a>
                                            <div class="card-body px-0 py-3">
                                                <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                                                    <a href="http://qingzhuti.com/554" rel="bookmark">内容类型（Post  Type）与文章形式（Post Format）</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <div class="card border-0">
                                            <a class="entry-img" href="http://qingzhuti.com/554">
                                                <img src="http://assets.qingzhuti.com/wp-content/themes/writing/assets/img/placeholder.png" alt="图片占位符" class="card-img rounded-0">
                                            </a>
                                            <div class="card-body px-0 py-3">
                                                <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                                                    <a href="http://qingzhuti.com/554" rel="bookmark">内容类型（Post  Type）与文章形式（Post Format）</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <div class="card border-0">
                                            <a class="entry-img" href="http://qingzhuti.com/554">
                                                <img src="http://assets.qingzhuti.com/wp-content/themes/writing/assets/img/placeholder.png" alt="图片占位符" class="card-img rounded-0">
                                            </a>
                                            <div class="card-body px-0 py-3">
                                                <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                                                    <a href="http://qingzhuti.com/554" rel="bookmark">内容类型（Post  Type）与文章形式（Post Format）</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <div class="card border-0">
                                            <a class="entry-img" href="http://qingzhuti.com/554">
                                                <img src="http://assets.qingzhuti.com/wp-content/themes/writing/assets/img/placeholder.png" alt="图片占位符" class="card-img rounded-0">
                                            </a>
                                            <div class="card-body px-0 py-3">
                                                <p class="card-title text-link-color line-clamp-2 text-overflow-ellipsis">
                                                    <a href="http://qingzhuti.com/554" rel="bookmark">内容类型（Post  Type）与文章形式（Post Format）</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </main>
                </div>

                <!--侧边栏 开始-->
                <?=$this->insert('Common/sidebar')?>
                <!--侧边栏 结束-->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</main>