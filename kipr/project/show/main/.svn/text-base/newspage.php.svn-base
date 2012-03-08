<?php if($news_item): ?>
<div class="b-news-header"><img src="/look/pic/page.png" alt="" /><h3><?=$news_item->name?></h3></div>
<div class="b-published">Опубликовано <?=$news_item->pubtime->format('j')?> <?=$months{$news_item->pubtime->format('n')}?> <?=$news_item->pubtime->format('Y')?> года</div>
<div class="b-newsrecord"><?=$news_item->text?>
<div class="b-sep"></div>
</div>
<div class="b-allnews"><a href="<?=$hostname?>allnews/">&larr; к списку новостей</a></div>
<a href="#add" class="b-messageadd">Добавить комментарий:</a>
<div class="b-form" id="hide">
    <?php if($error_message):?><div class="b-error"><?=$error_message?></div><?php endif;?>
    <form action="<?=$hostname?>commentadd/" method="post" id="commentaddform">
        <fieldset>
            <?php if($errors['name']):?><div class="b-error"><?=$errors['name']?></div><?php endif;?>
            <label for="name">Имя*:</label>
            <input type="text" name="name" id="name" value="<?=$answer['name']?>" maxlength="32" class="validate[required] b-input-text" />
            <?php if($errors['email']):?><div class="b-error"><?=$errors['email']?></div><?php endif;?>
            <label for="name">Почтовый ящик:</label>
            <input type="text" name="email" id="email" value="<?=$answer['email']?>" maxlength="80" class="validate[optional,custom[email]] b-input-text" />
            <label for="name">Защита от спама*:<a href="#update" id="update">обновить изображение</a></label>
            <input type="text" name="captcha" id="captcha" maxlength="4" class="validate[required,custom[onlyNumber]] b-input-text" />
            <img src="/guestbook/captcha/" id="cap" alt="Вы робот?" />
            <?php if($errors['text']):?><div class="b-error"><?=$errors['text']?></div><?php endif;?>
            <label for="name">Текст сообщения*:</label>
            <textarea name="text" cols="30" id="text" class="validate[required]" rows="10"><?=$answer['text']?></textarea>
            <input type="hidden" name="news" value="<?=$news_item->id?>" />
            <input type="submit" value="Отправить" class="b-input-submit" />
        </fieldset>
    </form>
</div>
    <?php if($news_item->newscomment):?>
        <div class="b-allcomments">
        <?php foreach($news_item->newscomment as $comment):?>
            <div class="b-newscomment">
                <div class="b-comment-text"><?=$comment->text?></div>
                <div class="b-comment-arrow"></div>
                <img src="/look/pic/user.png" alt="Комментарий" />
                <span class="b-comment-info"><b><?=$comment->name?></b></span><div class="b-comment-date"><?=$comment->pubtime->format('j')?> <?=$months{$comment->pubtime->format('n')}?> <?=$comment->pubtime->format('Y')?>, <?=$comment->pubtime->format('H:i')?></div>
            </div>
        <?php endforeach;?>
        </div>
    <?php endif;?>
<?php endif;?>
<?php if(!empty($errors)):?>
    <script type="text/javascript">
        $("#hide").show();
    </script>
<?php endif;?>