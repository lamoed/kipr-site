<div class="b-news-header"><h3>Редактирование новости</h3></div>
<div class="b-form">
    <?php if($errors['message']):?><div class="b-error"><?=$errors['message']?></div><?php endif;?>
    <form action="<?=$hostname?>admin/news/edit/<?=(isset($answer['news_id']))?$answer['news_id']:$news_item->id?>" method="post">
        <fieldset>
            <?php if($errors['name']): ?><div class="b-error"><?=$errors['name'] ?></div><?php endif; ?>
            <label for="name">Заголовок:</label>
            <input type="text" name="name" class="b-input-text" maxlength="200" value="<?=(isset($answer['name']))?$answer['name']:$news_item->name?>"/>
            <?php if($errors['announce']): ?><div class="b-error"><?=$errors['announce'] ?></div><?php endif; ?>
            <label for="announce">Анонс:</label>
            <textarea name="announce" cols="30" rows="5"><?=(isset($answer['announce']))?$answer['announce']:$news_item->announce?></textarea>
            <label for="newstext">Текст новости:</label>
            <textarea name="newstext" cols="30" rows="10"><?=(isset($answer['newstext']))?$answer['newstext']:$news_item->text?></textarea>
            <input type="submit" class="b-submit" value="Отправить" />
            </fieldset>
    </form>
</div>
<br />
<div class="b-news-header"><h3>Комментариев к новости: <?=count($news_item->newscomment)?></h3></div>
<?php if($news_item->newscomment): ?>
<div class="b-news-list">
    <ul>
        <?php foreach($news_item->newscomment as $record): ?>
        <li><span class="b-left b-guest-left"><img src="/look/pic/delete.png" alt="" onmouseout="document.body.style.cursor='default'" onmouseover="document.body.style.cursor='pointer'" onclick="sure(<?=$record->id?>, 'comments');" /><b><?=$record->name?></b> &mdash; <?=substr($record->text, 0, 45)?>...</span><span class="b-right b-guest-right"><?=$record->pubtime->format('d.m.Y H:i')?></span></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>