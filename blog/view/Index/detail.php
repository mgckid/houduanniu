<?php $this->layout('Layout/blog');?>
<section class="container">
    <div class="content-wrap">
        <div class="content">
            <header class="article-header">
                <h1 class="article-title"><?=$info['title']?></h1>
                <div class="article-meta">
                    <span class="item"><?=date('Y-m-d',strtotime($info['public_time']))?></span>
                    <span class="item">作者：<?=$info['editor']?></span>
                    <span class="item">分类：<a href="<?=U('Index/category',array('cate'=>$info['category_alias']))?>" rel="category tag"><?=$info['category']?></a> </span>
                    <span class="item post-views">阅读(<?=$info['click']?>)</span>
  <!--                  <span class="item">评论(1)</span>
                    <span class="item"></span>-->
                </div>
            </header>
            <article class="article-content">
                <?=htmlspecialchars_decode($info['content'])?>
            </article>
            <!--百度分享 开始-->
            <!--百度分享 结束-->

            <div class="article-tags">标签：
                <?php foreach ($info['tags'] as $value):?>
                    <a href="<?=U('Index/tag',array('tag_name'=>$value))?>" rel="tag"><?=$value?></a>
                <?php endforeach;?>
            </div>

            <nav class="article-nav">
                <span class="article-nav-prev">上一篇<br>
                    <?php if ($preInfo): ?>
                        <a href="<?= U('Index/detail', array('id' => $preInfo['title_alias'])) ?>"  rel="prev"><?= $preInfo['title'] ?></a>
                    <?php else: ?>
                        <a href="javascript:void(0)">无</a>
                    <?php endif; ?>
                </span>
                <span class="article-nav-next">下一篇<br>
                    <?php if ($nextInfo): ?>
                        <a href="<?=U('Index/detail',array('id'=>$nextInfo['title_alias']))?>" rel="next"><?=$nextInfo['title']?></a>
                    <?php else: ?>
                        <a href="javascript:void(0)">无</a>
                    <?php endif; ?>
                </span>
            </nav>

            <div class="relates">
                <div class="title"><h3>相关推荐</h3></div>
                <ul>
                    <?php foreach ($relatedPost as $value): ?>
                        <li><a href="<?= U('Index/detail', array('id' => $value['title_alias'])) ?>"><?= $value['title'] ?> </a> </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!-- 网易云回帖 开始-->
            <div id="cloud-tie-wrapper" class="cloud-tie-wrapper"></div>
            <script src="https://img1.cache.netease.com/f2e/tie/yun/sdk/loader.js"></script>
            <script>
                var cloudTieConfig = {
                    url: document.location.href,
                    sourceId: "",
                    productKey: "80da6417969849cd8d97555915390ec2",
                    target: "cloud-tie-wrapper"
                };
                var yunManualLoad = true;
                Tie.loader("aHR0cHM6Ly9hcGkuZ2VudGllLjE2My5jb20vcGMvbGl2ZXNjcmlwdC5odG1s", true);
            </script>
            <!-- 网易云回帖 结束-->
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
<!--统计文章阅览数 开始-->
<script src="/static/blog/js/ajaxForm/jquery.form.js"></script>
<form id="ajaxCountView" action="<?=U('Index/ajaxCountView')?>" method="post">
    <input type="hidden" name="id" value="<?=$info['id']?>"/>
</form>
<script>
    $(function(){
        $('#ajaxCountView').ajaxSubmit({
            dataType: 'json',
            error: function () {
                layer.msg('服务器连接错误')
            },
            sunccess:function(data){
                if(data.status==1){
                    $('.countView').html(data.data.click)
                }
            }
        });
    })
</script>
<!--统计文章阅览数 结束-->
