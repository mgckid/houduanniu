<?php $this->layout('Layout/blog');?>
<section class="container">
    <div class="content-wrap">
        <div class="content">
            <?php if(!empty($adList)):?>
            <!--大图轮播 注释-->
            <div id="focusslide" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php foreach ($adList['index_top_lunbo'] as $key =>$value):?>
                      <li data-target="#focusslide" data-slide-to="<?=$key?>" <?=$key==0?'class="active"':''?> ></li>
                    <?php endforeach;?>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <?php foreach ($adList['index_top_lunbo'] as $key =>$value):?>
                        <div class="item  <?=$key==0?'active':''?>">
                            <a target="_blank" href="<?=$value['ad_link']?>" title="<?=$value['ad_title']?>">
                                <img src="<?=$value['image_url']?>" style="width: 820px;height: 200px;">
                            </a>
                        </div>
                    <?php endforeach;?>
                </div>
                <a class="left carousel-control" href="#focusslide" role="button" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="right carousel-control" href="#focusslide" role="button" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
            <?php endif;?>


            <div class="title">
                <h3> 最新发布 </h3>
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
