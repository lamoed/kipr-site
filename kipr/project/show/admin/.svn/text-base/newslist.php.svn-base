<div class="b-news-header"><img src="/look/pic/news.png" alt="" /><h3>Управление новостями</h3><span class="b-margin"><img src="/look/pic/add.png" alt="" /><h3><a href="<?=$hostname?>admin/news/add/">Добавить новость</a></h3></span></div>
<?php if($news): ?>
<div class="b-news-list">
    <ul>
        <?php foreach($news as $news_item): ?>
        <li><span class="b-left"><img src="/look/pic/delete.png" alt="" onmouseout="document.body.style.cursor='default'" onmouseover="document.body.style.cursor='pointer'" onclick="sure(<?=$news_item->id?>, 'news');" /><a href="<?=$hostname?>admin/news/edit/<?=$news_item->id?>"><?=$news_item->name?></a></span><span class="b-right"><?=$news_item->pubtime->format('d.m.Y')?></span></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>
<?=$pagelist?>