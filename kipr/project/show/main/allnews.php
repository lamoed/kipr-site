<?php if($news):?>
<div class="b-news">
     <?php foreach($news as $news_item): ?>
    <div class="b-news-new">
        <h2><a href="<?=$hostname?>news/<?=$news_item->id?>"><?=$news_item->name?></a></h2>
        <div class="b-announce">
            <?=$news_item->announce?>
        </div>
        <div class="b-news-separator"></div>
        <div class="b-date">Опубликовано <b><?=$news_item->pubtime->format('d.m.y')?></b></div>
        <div class="b-comment"><img src="/look/pic/comment.png" alt="" />Комментариев <b><?=$news_item->text?></b></div>
    </div>
    <?php endforeach;?>
    <?=$pagelist?>
</div>
<?php endif;?>