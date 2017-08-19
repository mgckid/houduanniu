<?php $this->layout('Layout/blog');?>
<section class="container">
    <div class="content-wrap">
        <div class="content">
            <!--大图轮播 注释-->
<!--            <div id="focusslide" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#focusslide" data-slide-to="0" class="active"></li>
                    <li data-target="#focusslide" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <a target="_blank" href="###">
                            <img src="/static/blog/images/hs-xiu.jpg">
                        </a>
                    </div>
                    <div class="item">
                        <a target="_blank" href="###">
                            <img src="/static/blog/images/hs-xiu.jpg">
                        </a>
                    </div>
                </div>
                <a class="left carousel-control" href="#focusslide" role="button" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="right carousel-control" href="#focusslide" role="button" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>-->

<!--头条注释-->
  <!--          <article class="excerpt-minic excerpt-minic-index">
                <h2>
                    <a class="red" href="###">【今日观点】</a>
                    <a href="###" title="从下载看我们该如何做事-DUX主题演示">从下载看我们该如何做事</a>
                </h2>
                <p class="note">一次我下载几部电影，发现如果同时下载多部要等上几个小时，然后我把最想看的做个先后排序，去设置同时只能下载一部，结果是不到一杯茶功夫我就能看到最想看的电影。
                    这就像我们一段时间内想干成很多事情，是同时干还是有选择有顺序的干，结果很不一样。同时...</p>
            </article>-->

            <div class="pagetitle">
                <h3> <?=$cateInfo['name']?> </h3>
            </div>
            <?php foreach ($latestList as $key => $value): ?>
                <article class="excerpt excerpt-<?= ++$key ?> <?= $value['image_url'] ? '' : 'excerpt-text' ?>">
                    <?php if ($value['image_url']): ?>
                        <a class="focus" href="<?= U('Index/detail', array('id' => $value['title_alias'])) ?>">
                            <img data-src="<?= $value['image_url'] ?>"
                                 alt="<?= $value['title'] ?>"
                                 src="/static/blog/images/thumbnail.gif"
                                 class="thumb"/>
                        </a>
                    <?php endif; ?>
                    <header>
                        <a class="cat" href="<?=U('Index/category',array('cate'=>$value['category_alias']))?>"><?=$value['category']?><i></i></a>

                        <h2><a href="<?= U('Index/detail', array('id' => $value['title_alias'])) ?>"
                               title="<?= $value['title'] ?>"><?= $value['title'] ?></a></h2>
                    </header>
                    <p class="meta">
                        <time><i class="fa fa-clock-o"></i><?= date('Y-m-d', strtotime($value['public_time'])) ?></time>
                        <span class="author">
						    <i class="fa fa-user"></i><?= $value['editor'] ?>
                        </span>
                        <span class="pv">
                            <i class="fa fa-eye"></i>阅读(<?= $value['click'] ?>)
                        </span>
                        <span>
                            <a class="pc" href="###">
                                <i class="fa fa-comments-o"></i>评论(0)
                            </a>
                        </span>
                        <?php if($value['tags']):?>
                        <span>
                            <i class="fa fa-tags"></i>
                            <?php foreach ($value['tags'] as $v): ?>
                                <a href="<?= U('index/tag', array('tag_name' => $v)) ?>"><?= $v ?></a>
                            <?php endforeach; ?>
                        </span>
                        <?php endif;?>
                    </p>
                    <p class="note"> <?= $value['description'] ?></p>
                </article>
            <?php endforeach;?>
            <div class="pagination">
                <?=$pages?>
            </div>
        </div>
    </div>
    <aside class="sidebar">

        <!--热门文章 开始-->
        <?= W('Blog/hotPost',[5]) ?>
        <!--热门文章  结束-->
        <!--热门标签 开始-->
        <?= W('Blog/hotTag', [20]) ?>
        <!--热门标签 结束-->
    </aside>
</section>
