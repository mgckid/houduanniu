<?php $this->layout('Layout/blog'); ?>
<section class="container">
    <div class="content-wrap">
        <div class="content">
            <div class="pagetitle"><h1>标签：<?=$tagName?></h1></div>
            <?php foreach ($postList as $key => $value): ?>
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
                    <h2><a href="<?= U('Index/detail', array('id' => $value['title_alias'])) ?>" title="<?= $value['title'] ?>">
                            <?= $value['title'] ?>
                        </a>
                    </h2>
                </header>
                <p class="meta"></p>

                <p class="note"><?= $value['description'] ?></p>
            </article>
            <?php endforeach;?>
            <div class="pagination">
                <?=$pages?>
            </div>
        </div>
    </div>
    <aside class="sidebar">
        <!--热门文章 开始-->
        <?= W('Blog/hotPost',[10]) ?>
        <!--热门文章  结束-->
        <!--热门标签 开始-->
        <?= W('Blog/hotTag', [20]) ?>
        <!--热门标签 结束-->
    </aside>
</section>
