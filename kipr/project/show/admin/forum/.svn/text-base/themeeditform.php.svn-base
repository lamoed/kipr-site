<form name="themeedit" action="<?=$hostname?>admin/forum/theme/edit/<?=isset($answer['id_theme'])?$answer['id_theme']:$theme->id_theme?>" method="post">
    <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <table>
        <tr>
            <?php if($errors['name']): ?><div class="b-error"><?=$errors['name'] ?></div><?php endif; ?>
            <td width="150" class="field" valign=top>Название темы&nbsp;*:</td>
            <td  class="field" valign=top>
                <input type="text" name="name" value="<?=(isset($answer['name'])) ? $answer['name'] : $theme->name ?>" size="41" maxlength="255">
            </td>
        </tr>
        <tr>
            <?php if($errors['author']): ?><div class="b-error"><?=$errors['author'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Автор&nbsp;*:</td>
            <td  class="field" valign=top>
                <input type="text" name="author" value="<?=(isset($answer['author'])) ? $answer['author'] : $theme->author ?>" size=41 maxlength=255>
            </td>
        </tr>
        <?php if(!empty($categories) || count($categories) > 1): ?>
            <tr>
                <?php if($errors['newforum']): ?><div class="b-error"><?=$errors['newforum'] ?></div><?php endif; ?>
                <td width="150" class="field" valign="top">Переместить в форум:</td>
                <td class="field" valign="top">
                    <select name="newforum">
                        <option value='0' <?=(empty($answer['newforum']) && empty($theme->id_forum)) ? "selected='selected'" : "" ?>>Не перемещать</option>
                        <?php foreach($categories as $key => $category): ?>
                            <option value='<?=$category->id_forum ?>' <?=($answer['newforum'] == $category->id_forum || $theme->id_forum == $category->id_forum) ? "selected='selected'" : "" ?>><?=$category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php endif; ?>
            <tr>
                <td  class="field"></td>
                <td  class="field">
                    <input class="button" type="submit" value="Редактировать">
                </td>
            </tr>
    </table>
</form>