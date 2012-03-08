<div class="b-news-header"><h3>Добавление новости</h3></div>
<div class="b-form">
    <?php if($errors['message']):?><div class="b-error"><?=$errors['message']?></div><?php endif;?>
    <form action="<?=$hostname?>admin/news/add/" method="post">
        <fieldset>
            <?php if($errors['name']): ?><div class="b-error"><?=$errors['name'] ?></div><?php endif; ?>
            <label for="name">Заголовок:</label>
            <input type="text" name="name" class="b-input-text" maxlength="200" value="<?=$answer['name']?>" />
            <?php if($errors['announce']): ?><div class="b-error"><?=$errors['announce'] ?></div><?php endif; ?>
            <label for="announce">Анонс:</label>
            <textarea name="announce" cols="30" rows="5"><?=$answer['announce']?></textarea>
            <label for="newstext">Текст новости:</label>
            <textarea name="newstext" cols="30" rows="10"><?=$answer['newstext']?></textarea>
            <input type="submit" class="b-submit" value="Отправить" />
            </fieldset>
    </form>
</div>