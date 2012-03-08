<form name="useredit" action="<?=$hostname?>admin/forum/user/edit/<?=isset($answer['id_author'])?$answer['id_author']:$user->id_author?>/page/<?=$page?>" method="post">
    <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <table>
        <tr>
            <td width="150" class="field" valign="top">Пользователь:</td>
            <td class="field" valign="top"><?=(isset($answer['username'])) ? $answer['username'] : $user->name ?><br /><br /></td>
        </tr>
        <tr>
            <td width="150" class="field" valign="top">Пароль:</td>
            <td  class="field" valign="top">
                <input type="password" name="pass" value="" size="41" maxlength="255">
            </td>
        </tr>
        <tr>
            <td width="150" class="field" valign="top">Повтор пароля:</td>
            <td  class="field" valign="top">
                <input type="password" name="passagain" value="" size="41" maxlength="255">
            </td>
        </tr>
        <tr>
            <?php if($errors['email']): ?><div class="b-error"><?=$errors['email'] ?></div><?php endif; ?>
            <td width="150" class="field" valign="top">E-mail:</td>
            <td  class="field" valign="top">
                <input type="text" name="email" value="<?=(isset($answer['email'])) ? $answer['email'] : $user->email ?>" size="41" maxlength="255">
            </td>
        </tr>
        <tr>
            <?php if($errors['url']): ?><div class="b-error"><?=$errors['url'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>URL:</td>
            <td  class="field" valign="top">
                <input type="text" name="url" value="<?=(isset($answer['url'])) ? $answer['url'] : $user->url ?>" size=41 maxlength=255>
            </td>
        </tr>
        <tr>
            <?php if($errors['icq']): ?><div class="b-error"><?=$errors['icq'] ?></div><?php endif; ?>
            <td width=150 class="field" valign="top">ICQ:</td>
            <td  class="field" valign="top">
                <input type="text" name="icq" value="<?=(isset($answer['icq'])) ? $answer['icq'] : $user->icq ?>" size=41 maxlength=255>
            </td>
        </tr>
        <tr>
            <?php if($errors['about']): ?><div class="b-error"><?=$errors['about'] ?></div><?php endif; ?>
            <td width=150 class="field" valign="top">О себе:</td>
            <td  class="field" valign="top">
                 <textarea name="about" cols="35" rows="7"><?=(isset($answer['about'])) ? $answer['about'] : $user->about ?></textarea>
            </td>
        </tr>
        <?php if(!empty($user->photo) || !empty($answer['photo_url'])):?>
        <tr>
            <?php if($errors['photo']): ?><div class="b-error"><?=$errors['photo'] ?></div><?php endif; ?>
            <td width=150 class="field" valign=top>Удалить фото?:</td>
            <td  class="field" valign="top">
                <input type="checkbox" name="photo" <?=($answer['photo'] == true)?"checked='checked'":""?> />
            </td>
        </tr>
        <?php endif;?>
        <input type="hidden" name="photo_url" value="<?=(isset($answer['photo_url'])) ? $answer['photo_url'] : $user->photo ?>" />
        <input type="hidden" name="username" value="<?=(isset($answer['username'])) ? $answer['username'] : $user->name ?>" />
        <tr>
            <?php if($errors['themes']): ?><div class="b-error"><?=$errors['themes'] ?></div><?php endif; ?>
            <td width=150 class="field" valign="top">Количество сообщений:</td>
            <td  class="field" valign="top">
                <input type="text" name="themes" value="<?=(isset($answer['themes'])) ? $answer['themes'] : $user->themes ?>" size=41 maxlength=255>
            </td>
        </tr>
        <tr>
            <?php if($errors['statususer']): ?><div class="b-error"><?=$errors['statususer'] ?></div><?php endif; ?>
            <td width="150" class="field" valign="top">Статус пользователя:</td>
            <td class="field" valign="top">
                <select name="statususer">
                    <option value='1' <?=($answer['statususer'] == '1' || (!empty($user) && $user->statususer == '')) ? "selected='selected'" : "" ?>>Пользователь</option>
                    <option value='2' <?=($answer['statususer'] == '2' || (!empty($user) && $user->statususer == 'admin')) ? "selected='selected'" : "" ?>>Администратор</option>
                </select>
            </td>
        </tr>
        <tr>
            <td  class="field"></td>
            <td  class="field">
                <input class="button" type="submit" value="Редактировать" />
            </td>
        </tr>
    </table>
</form>