<div class="b-news-header"><h3>Редактирование страницы преподавателя</h3></div>
<div class="b-form">
    <?php if($errors['message']):?><div class="b-error"><?=$errors['message']?></div><?php endif;?>
    <form action="<?=$hostname?>admin/lecturers/edit/<?=(isset($answer['linkname']))?$answer['linkname']:$lecturer->linkname?>" method="post">
        <fieldset>
            <?php if($errors['header']): ?><div class="b-error"><?=$errors['header'] ?></div><?php endif; ?>
            <label for="header">Ф.И.О:</label>
            <input type="text" name="header" class="b-input-text" maxlength="200" value="<?=(isset($answer['header']))?$answer['header']:$lecturer->header?>" />
            <?php if($errors['rang']): ?><div class="b-error"><?=$errors['rang'] ?></div><?php endif; ?>
            <label for="additional">Должность:</label>
            <input type="text" name="additional" class="b-input-text" maxlength="200" value="<?=(isset($answer['rang']))?$answer['rang']:$lecturer->additional?>" />
            <?php if($errors['linkname']): ?><div class="b-error"><?=$errors['linkname'] ?></div><?php endif; ?>
            <label for="linkname">Ссылка на страницу преподавателя:</label>
            <input type="text" name="linkname" class="b-input-text" maxlength="200" value="<?=(isset($answer['linkname']))?$answer['linkname']:$lecturer->linkname?>" />
            <label for="lectext">Текст:</label>
            <textarea name="lectext" cols="30" rows="10"><?=(isset($answer['lectext']))?$answer['lectext']:$lecturer->text?></textarea>
            <input type="submit" class="b-submit" value="Отправить" />
            </fieldset>
    </form>
</div>