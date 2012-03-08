<div class="b-news-header"><h3>Редактирование документа</h3></div>
<br /><a href="<?=(isset($answer['link']))?$answer['link']:$document->link.'/'.$document->filename?>" class="b-path">Путь к документу: <?=(isset($answer['link']))?$answer['link']:$document->link.'/'.$document->filename?></a><br /><br />
<div class="b-form">
    <?php if($errors['message']):?><div class="b-error"><?=$errors['message']?></div><?php endif;?>
    <form action="<?=$hostname?>admin/documents/edit/<?=(isset($answer['id']))?$answer['id']:$document->id?>" method="post">
        <fieldset>
             <?php if($errors['name']): ?><div class="b-error"><?=$errors['name'] ?></div><?php endif; ?>
            <label for="name">Название:</label>
            <input type="text" name="name" class="b-input-text" maxlength="200" value="<?=(isset($answer['name']))?$answer['name']:$document->name?>"/>
            <?php if($errors['comment']): ?><div class="b-error"><?=$errors['comment'] ?></div><?php endif; ?>
            <label for="comment">Комментарий:</label>
            <textarea name="comment" cols="30" rows="5"><?=(isset($answer['comment']))?$answer['comment']:$document->comment?></textarea>
            <?php isset($answer['important'])?$answer['important'] : $document->important == 1 ? $checked = "checked = checked" : "";?>
            <br /><input type="checkbox" name="important" value="1" <?=$checked?> />Разместить на главной странице<br />
            <input type="hidden" name="folder" value="<?=(isset($answer['folder']))?$answer['folder']:$document->folder?>" />
            <input type="hidden" name="link" value="<?=(isset($answer['link']))?$answer['link']:$document->link.'/'.$document->filename?>" />
            <input type="submit" class="b-submit" value="Отправить" />
            </fieldset>
    </form>
</div>