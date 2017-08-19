<div class="widget widget_ui_tags"><h3>热门标签</h3>

    <div class="items">
        <?php foreach ($hotTag as $value): ?>
            <a href="<?= U('Index/tag', array('tag_name' => $value['tag_name'])) ?>"><?= $value['tag_name'] ?> (<?= $value['post_count'] ?>)</a>
        <?php endforeach; ?>
    </div>
</div>