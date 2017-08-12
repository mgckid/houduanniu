<?php $this->layout('Layout/blog'); ?>
<section class="container container-page">
    <div class="pageside">
        <div class="pagemenus">
            <ul class="pagemenu">
                <?php foreach ($cates as $value): ?>
                    <li <?= $cateInfo['id'] == $value['id'] ? 'class="active"' : '' ?> >
                        <a href="<?= U('Index/category', array('cate' => $value['alias'])) ?>"><?=$value['name']?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="content">
        <header class="article-header">
            <h1 class="article-title"><a href="http://www.daqianduan.com/about"><?=$value['name']?></a></h1>
        </header>
        <article class="article-content">
            <?=htmlspecialchars_decode($value['page_content'])?>
        </article>
        <p>&nbsp;</p>
    </div>
</section>

