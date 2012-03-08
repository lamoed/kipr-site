<?php if($records): ?>
<div class="b-gbook">
    <div class="b-gbook-header"><img src="/look/pic/book.png" alt="" /><h3>Гостевая книга</h3></div>
        <?php foreach($records as $record): ?>
        <?php $i++; $i % 2 == 0 ? $class = 'b-record-green': $class = 'b-record-brown';?>
    <div class="<?=$class?>">
        <div class="b-record-header">
            <span class="b-text"><img src="/look/pic/user.png" alt="" /><?=$record->name?> | <?=$record->writetime->format('d/m/Y')?></span><span class="b-time"><img src="/look/pic/clock.png" alt="" /><?=$record->writetime->format('H:i')?></span>
        </div>
        <?=$record->message?>
    </div>
        <?php endforeach; ?>
    <?=$pagelist?>
</div>
<?php else: ?>
<h2>Сообщений нет</h2>
<?php endif; ?>
<a href="#add" class="b-messageadd">Добавить сообщение:</a>
<div class="b-form" id="hide">
    <?php if($error_message):?><div class="b-error"><?=$error_message?></div><?php endif;?>
    <form action="<?=$hostname?>guestbook/add" method="post" id="guestbookform">
        <fieldset>
            <label for="name">Имя*:</label>
            <?php if($errors['name']):?><div class="b-error"><?=$errors['name']?></div><?php endif;?>
            <input type="text" name="name" id="name" value="<?=$answer['name']?>" maxlength="32" class="validate[required] b-input-text" />
            <?php if($errors['email']):?><div class="b-error"><?=$errors['email']?></div><?php endif;?>
            <label for="name">Почтовый ящик:</label>
            <input type="text" id="email" name="email" value="<?=$answer['email']?>" maxlength="80" class="validate[optional,custom[email]] b-input-text" />
            <label for="name">Защита от спама*:<a href="#update" id="update">обновить изображение</a></label>
            <input type="text" id="captcha" name="captcha" maxlength="4" class="validate[required,custom[onlyNumber]] b-input-text" />
            <img src="/guestbook/captcha/" id="cap" alt="Вы робот?" />
            <?php if($errors['message']):?><div class="b-error"><?=$errors['message']?></div><?php endif;?>
            <label for="name">Текст сообщения*:</label>
            <textarea name="message" id="message" class="validate[required]" cols="30" rows="10"><?=$answer['message']?></textarea>
            <input type="submit" value="Отправить" class="b-input-submit" />
        </fieldset>
    </form>
</div>
<?php if(!empty($errors)):?>
    <script type="text/javascript">
        $("#hide").show();
    </script>
<?php endif;?>