<form name="postedit" action="<?=$hostname?>admin/forum/post/edit/<?=isset($answer['id_post'])?$answer['id_post']:$post->id_post?>/theme/<?=$theme?>" method="post">
    <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <table>
        <tr>
            <?php if($errors['author']): ?><div class="b-error"><?=$errors['author'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Автор&nbsp;*:</td>
            <td  class="field" valign=top>
                <input type="text" name="author" value="<?=(isset($answer['author'])) ? $answer['author'] : $post->author ?>" size=41 maxlength=255>
            </td>
        </tr>
        <tr>
            <?php if($errors['name']): ?><div class="b-error"><?=$errors['name'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Сообщение&nbsp;*:</td>
            <td  class="field" valign=top>
                <textarea rows="15" cols="60" name="name"><?=(isset($answer['name'])) ? $answer['name'] : $post->name ?></textarea>
            </td>
        </tr>
            <tr>
                <td  class="field"></td>
                <td  class="field">
                    <input class="button" type="submit" value="Редактировать">
                </td>
            </tr>
    </table>
</form>