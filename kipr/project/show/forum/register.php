<div class="b-center">
    <div class="b-news-header"><h3>Регистрация на форуме</h3></div>
    <div class=bodyform>
        <form enctype="multipart/form-data" action="<?=$hostname?>forum/register/" method="post" id="registerform">
            <fieldset>
                <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
                <?php if($errors['author']): ?><div class="b-error"><?=$errors['author'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="author">Имя *:</label></p>
                <input size="25" class="validate[required] input" type="text" name="author" id="author" maxlength="100" size="61" value="<?=$answer['author']?>" />
                <?php if($errors['pswrd']): ?><div class="b-error"><?=$errors['pswrd'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="pswrd">Пароль *:</label></p>
                <input size="25" id="pswrd" class="validate[required] input" type="password" name="pswrd" maxlength="100" size="61" value="" />
                <p class="fieldname"><label for="pswrd_again">Повтор пароля *:</label></p>
                <input size="25" class="validate[required,confirm[pswrd]] input" id="pswrd_again" type="password" name="pswrd_again" maxlength="100" size="61" value="" />
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
                     <input size="61" class="input" type="file" name="photo" /><br/><br/>
                <?php endif;?>
                <input type="submit" value="Зарегистрироваться" class="button" /><br/><br/>
            </fieldset>
        </form>
        <div class="blockremark">Для регистрации заполните необходимые данные и нажмите кнопку "Зарегистрировать".
            Обязательные поля отмечены звездочкой (*).
        </div>
        <p class=linkbackbig><a class="b-back" href="<?=$hostname?>forum/category/<?=$category?>">Вернуться к списку тем</a></p>
    </div>
</div>