<footer class="footer">
    <div class="container">
        <div class="flinks">
            <strong>友情链接</strong>
            <ul class='xoxo blogroll'>
                <?php foreach($flink as $value):?>
                    <li><a href="<?=$value['furl']?>" title="<?=$value['fname']?>" target="_blank"><?=$value['fname']?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <p> &copy; 2013 - <?=date('Y',time())?>
            <a href="/">后端牛博客</a>
<!--            &nbsp;<a href="###">网站地图</a>-->
        </p>
    </div>
</footer>