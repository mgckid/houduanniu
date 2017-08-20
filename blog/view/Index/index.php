<?php $this->layout('Layout/index')?>
<main class="site-content">
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="main-content">
                    <div class="posts">
                        <?php foreach($list_data as $value):?>
                            <?php if(empty($value['main_image'])):?>
                                <div class="card card-shadow">
                                    <div class="card-body">
                                        <h2 class="card-title h5 text-link-color line-clamp-2 text-overflow-ellipsis">
                                            <a href="<?=U('Post/detail',['id'=>$value['title_alias']])?>" rel="<?=$value['title']?>"><?=$value['title']?></a>
                                        </h2>
                                        <p class="card-text hidden-sm-down">
                                            <?=msubstr($value['description'],0,100)?>
                                        </p>
                                        <p class="card-text text-link-color-muted">
                                            <small>
                                                <span class="post-time"><?=date('Y年m月d日',strtotime($value['created']))?></span>
                                                <span class="post-category"> &nbsp;&bull;&nbsp; <a href="###" rel="category tag"><?=$value['category_name']?></a></span>
                                            </small>
                                        </p>

                                    </div>
                                </div>
                            <?php else:?>
                                <div class="card card-shadow">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-4">
                                                <a class="entry-img" href="<?=U('Post/detail',['id'=>$value['title_alias']])?>" rel="<?=$value['title']?>">
                                                    <img width="300" height="169" src="<?=getImage($value['main_image'])?>" class="img-fluid wp-post-image" alt="<?=$value['title']?>"  sizes="(max-width: 300px) 100vw, 300px" />
                                                </a>
                                            </div>

                                            <div class="col-8">
                                                <h2 class="card-title h5 text-link-color line-clamp-2 text-overflow-ellipsis mb-3">
                                                    <a href="<?=U('Post/detail',['id'=>$value['title_alias']])?>" rel="<?=$value['title']?>"><?=$value['title']?></a>
                                                </h2>
                                                <p class="card-text mt-3 hidden-sm-down">
                                                    <?=msubstr($value['description'],0,100)?>
                                                </p>
                                                <p class="card-text text-link-color-muted">
                                                    <small>
                                                        <span class="post-time"><?=date('Y年m月d日',strtotime($value['created']))?></span>
                                                        <span class="post-category"> &nbsp;&bull;&nbsp; <a href="###" rel="category tag"><?=$value['category_name']?></a></span>
                                                    </small>
                                                </p>
                                            </div>
                                        </div><!-- ./row -->
                                    </div>
                                </div>
                            <?php endif;?>

                        <?php endforeach;?>
                    </div>
                    <div class="pagination">
                        <nav aria-label="Page navigation">
                            <?=$pages?>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 hidden-md-down">
                <div class="sidebar">
                    <aside class="widget card-shadow widget_text">
                        <h4 class="widget-header h6">WordPress主题：Writing</h4>

                        <div class="textwidget"><p>特色：<span class="badge badge-primary mr-2">自适应</span><span
                                    class="badge badge-warning mr-2">博客</span><span class="badge badge-info">主题选项</span></p>

                            <p>本博客主题免费下载：</p>

                            <p><a class="btn btn-warning btn-sm"
                                  href="http://blog.houduanniu.com/theme/writing?utm_source=site&amp;utm_medium=siebar">去下载</a>
                                v0.8.0</p>

                            <p>QQ群：189678769</p>
                        </div>
                    </aside>
                    <aside class="widget card-shadow d_tag"><h4 class="widget-header h6">热门标签</h4>

                        <div class="hot-tags">
                            <a href="###" class="btn btn-light btn-sm">如何写博客</a>
                        </div>
                    </aside>
                    <aside class="widget card-shadow widget_lean_posts">
                        <h4 class="widget-header h6">最近文章</h4>

                        <ul class="list-unstyled">
                            <li>
                                <a href="##" rel="bookmark" title="Writing-v0.8更新日志">Writing-v0.8更新日志</a>
                            </li>
                            <!--./li-->
                            <li>
                                <a href="##" rel="bookmark" title="Writing-v0.8更新日志">Writing-v0.8更新日志</a>
                            </li>
                            <!--./li-->
                            <li>
                                <a href="##" rel="bookmark" title="Writing-v0.8更新日志">Writing-v0.8更新日志</a>
                            </li>
                            <!--./li-->
                            <li>
                                <a href="##" rel="bookmark" title="Writing-v0.8更新日志">Writing-v0.8更新日志</a>
                            </li>
                            <!--./li-->
                            <li>
                                <a href="##" rel="bookmark" title="Writing-v0.8更新日志">Writing-v0.8更新日志</a>
                            </li>
                            <!--./li-->

                        </ul>
                        <!--./ul-->

                    </aside>
                    <aside class="widget card-shadow widget_nav_menu"><h4 class="widget-header h6">友情链接</h4>

                        <div>
                            <ul class="menu">
                                <li><a href="###">一个女产品经理的博客</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div><!--/.row-->
    </div>
</main>