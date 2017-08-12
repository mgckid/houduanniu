<?php $this->layout('Layout/blog'); ?>
<style>
    .page_tags .items a {
        color: #999;
        background-color: #f6f6f6;
        float: left;
        width: 15.3333%;
        margin: 0 1% 1% 0;
        padding: 0 8px;
        font-size: 12px;
        height: 29px;
        line-height: 29px;
        overflow: hidden;
    }
    .page_tags .items a:hover {
        color: #fff;
        background-color: #45B6F7;
    }
</style>
<div class="container container-no-sidebar">
    <div class="content">
        <header class="article-header">
            <h1 class="article-title text-left">标签集合</h1>
        </header>
        <div class="page_tags" style="top: 0px;">
            <div class="items">
                <?php foreach ($tagList as $value): ?>
                    <a href="<?= U('Index/tag', array('tag_name' => $value['tag_name'])) ?>"><?= $value['tag_name'] ?> (<?= $value['post_count'] ?>)</a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
