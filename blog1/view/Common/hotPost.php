<div class="widget widget_ui_posts"><h3>热门文章</h3>
    <ul>
        <?php foreach ($hotPost as $value): ?>
            <li>
                <a href="<?= U('Index/detail', array('id' => $value['title_alias'])) ?>">
                            <span class="thumbnail"><img
                                    data-src="<?=$value['image_url']?>"
                                    alt="<?=$value['title']?>"
                                    src="/static/blog/images/thumbnail.gif" class="thumb"></span>
                    <span class="text"><?=$value['title']?></span>
                    <span class="muted"><?= date('Y-m-d', strtotime($value['public_time'])) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>