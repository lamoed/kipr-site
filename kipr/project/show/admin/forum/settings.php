<form name="settingsedit" action="<?=$hostname?>admin/forum/settings/" method="post">
    <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <table>
        <tr>
            <?php if($errors['nameforum']): ?><div class="b-error"><?=$errors['nameforum'] ?></div><?php endif; ?>
            <td width="150" class="field" valign="top">Название форума&nbsp;*:</td>
            <td  class="field" valign="top">
                <input type="text" name="nameforum" value="<?=(isset($answer['nameforum'])) ? $answer['nameforum'] : $settings->forum_name ?>" size="41" maxlength="255">
            </td>
        </tr>
        <tr>
            <?php if($errors['numberthemes']): ?><div class="b-error"><?=$errors['numberthemes'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Количество тем на странице&nbsp;*:</td>
            <td  class="field" valign="top">
                <input type="text" name="numberthemes" value="<?=(isset($answer['numberthemes'])) ? $answer['numberthemes'] : $settings->forum_themes ?>" size=41 maxlength=255>
            </td>
        </tr>
        <tr>
            <?php if($errors['icq']): ?><div class="b-error"><?=$errors['cooktime'] ?></div><?php endif; ?>
            <td width=150 class="field" valign="top">Срок действия cookie, суток:</td>
            <td  class="field" valign="top">
                <input type="text" name="cooktime" value="<?=(isset($answer['cooktime'])) ? $answer['cooktime'] : $settings->forum_cooktime ?>" size=41 maxlength=255>
            </td>
        </tr>
        <tr>
            <?php if($errors['avatar']): ?><div class="b-error"><?=$errors['avatar'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Разрешить загрузку аватаров:</td>
            <td  class="field" valign="top">
                <input type="checkbox" name="avatar" <?=($answer['avatar'] == true || $settings->forum_photoload)?"checked='checked'":""?> />
            </td>
        </tr>
        <tr>
            <td  class="field"></td>
            <td  class="field">
                <input class="button" type="submit" value="Сохранить" />
            </td>
        </tr>
    </table>
</form>