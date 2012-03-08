<form name="categoryadd" action="<?=$hostname?>admin/forum/category/add/" method="post">
    <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <table>
        <tr>
            <?php if($errors['name']): ?><div class="b-error"><?=$errors['name'] ?></div><?php endif; ?>
            <td width=150 class="field" valign="top">Название&nbsp;*:</td>
            <td  class="field" valign="top">
                <input type="text" name="name" value="<?=$answer['name']?>" size="41" maxlength="255" />
            </td>
        </tr>
        <tr>
            <?php if($errors['logo']): ?><div class="b-error"><?=$errors['logo'] ?></div><?php endif; ?>
            <td width="150" class="field" valign="top">Краткое описание&nbsp;*:</td>
            <td  class="field" valign="top">
                <textarea name="logo" cols="35" rows="7"><?=$answer['logo']?></textarea>
            </td>
        </tr>
        <tr>
            <?php if($errors['rule']): ?><div class="b-error"><?=$errors['rule'] ?></div><?php endif; ?>
            <td width="150" class="field" valign=top>Правила форума:</td>
            <td  class="field" valign="top">
                <textarea name="rule" cols="35" rows="7"><?=$answer['rule']?></textarea>
            </td>
        </tr>
        <tr>
            <?php if($errors['pos']): ?><div class="b-error"><?=$errors['pos'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Позиция&nbsp;*:</td>
            <td  class="field" valign="top">
                <input type="text" name="pos" value="<?=isset($answer['pos'])?$answer['pos']:500?>" size="41" maxlength="255" />
            </td>
        </tr>
        <tr>
            <?php if($errors['hide']): ?><div class="b-error"><?=$errors['hide'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Отображать:</td>
            <td  class="field" valign="top">
                <input type="checkbox" name="hide" <?=(isset($answer['hide']) && $answer['hide'] == false)?"":"checked='checked'"?> />
            </td>
        </tr>
        <tr>
            <td  class="field"></td>
            <td  class="field">
                <input class="button" type="submit" value="Добавить">
            </td>
        </tr>
    </table>
</form>