<div class="b-center">
    <div class="b-news-header"><h3>Вход на форум</h3></div>
    <div class=bodyform>
        <form enctype="multipart/form-data" action="<?=$hostname?>forum/login/" method="post" id="loginform">
            <fieldset>
                <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
                <?php if($errors['login']): ?><div class="b-error"><?=$errors['login'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="login">Имя:</label></p>
                <input size="25" class="validate[required] input" type="text" id="login" name="login" maxlength="100" size="61" value="<?=$answer['login']?>" /><br />
                <?php if($errors['pswrd']): ?><div class="b-error"><?=$errors['pswrd'] ?></div><?php endif; ?>
                <p class="fieldname"><label for="pswrd">Пароль:</label></p>
                <input size="25" class="validate[required] input" type="password" id="pswrd" name="pswrd" maxlength="100" size="61" value="" /><br/><br/>
                <input type="submit" value="Отправить" class="button" /><br/><br/>
            </fieldset>
        </form>
        <div class="blockremark">
            Для авторизации Вам необходимо ввести ваше имя и пароль. Пройдите <a href="<?=$hostname?>forum/register">регистрацию</a>, если вы еще не зарегистрированы на форуме.
        </div>
        <p class=linkbackbig><a class="b-back" href="<?=$hostname?>forum/category/<?=$category?>">Вернуться к списку тем</a></p>
    </div>
</div>