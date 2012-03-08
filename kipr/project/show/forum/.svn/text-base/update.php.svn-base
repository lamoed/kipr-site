<div class="b-center">
    <div class="b-news-header"><h3>Обновление личных данных</h3></div>
    <div class=bodyform>
         <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
        <table>
            <tr>
                <td>
                    <p class="fieldname">Имя:</p>
                </td>
                <td>
                    <p class="authortext"><?=$answer['author']?></p>
                </td>
            </tr>
        </table>
        <form enctype="multipart/form-data" action="<?=$hostname?>forum/update/" method="post" id="userupdateform">
            <fieldset>
                <?php if($errors['pswrd']): ?><div class="b-error"><?=$errors['pswrd'] ?></div><?php endif; ?>
                <input type="hidden" name="id_author" value="<?=$answer['id_author']?>" />
                <input type="hidden" name="category" value="<?=$answer['category']?>" />
                <p class="fieldname"><label for="pswrd">Новый пароль:</label></p>
                <input size="25" class="input" type="password" id="pswrd" name="pswrd" maxlength="100" size="61" value="" />
                <p class="fieldname"><label for="pswrd_again">Повтор пароля:</label></p>
                <input size="25" class="validate[optional,confirm[pswrd]] input" type="password" id="pswrd_again" name="pswrd_again" maxlength="100" size="61" value="" />
                <?php if($errors['email']): ?><div class="b-error"><?=$errors['email'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="name">Почтовый ящик *:</label></p>
                <input size="25" class="validate[required,custom[email]] input" type="text" id="email" name="email" maxlength="100" size="61" value="<?=$answer['email']?>" />
                <?php if($errors['icq']): ?><div class="b-error"><?=$errors['icq'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="icq">ICQ:</label></p>
                <input size="25" class="validate[optional,custom[onlyNumber]] input" type="text" id="icq" name="icq" maxlength="100" size="61" value="<?=$answer['icq']?>" />
                <?php if($errors['url']): ?><div class="b-error"><?=$errors['url'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="url">URL:</label></p>
                <input size="74" class="input" type="text" id="url" name="url" maxlength="200" size="61" value="<?=$answer['url']?>" />
                <p class="fieldname"><label for="about">О&nbsp;себе:</label></p>
                <textarea class="input" name="about" cols="76" rows="3"  maxlength="500"><?=$answer['about']?></textarea>
                 <?php if($errors['photo']): ?><div class="b-error"><?=$errors['photo'] ?></div><?php endif; ?>
                 <?php if($settings->forum_photoload):?>
                     <p class="fieldname"><label for="photo">Аватар: (не более 40 Кб)</label></p>
                     <input size="61" class="input" type="file" name="photo" />
                <?php endif;?>
                <?php if(!empty($answer['photo'])):?><img src="<?=$answer['photo']?>" title="Аватар" alt="Аватар" /><?php endif;?>
                <br/><br/>
                <input type="submit" value="Обновить" class="button" /><br/><br/>
            </fieldset>
        </form>
        <div class="blockremark">Исправьте необходимые данные и нажмите кнопку "Обновить". Обязательные поля отмечены звездочкой (*).
        </div>
        <p class=linkbackbig><a class="b-back" href="<?=$hostname?>forum/category/<?=$answer['category']?>">Вернуться к списку тем</a></p>
    </div>
</div>