<div class="b-news-header"><img src="/look/pic/settings.png" alt="" /><h3>Настройки сайта:</h3></div>
<?php if($settings):?>
<div class="b-form b-admin-form">
    <?php if($message):?><div class="b-error"><?=$message?></div><?php endif;?>
    <form action="<?=$hostname?>admin/changesettings/" method="post">
        <fieldset>
            <label for="maxnews">Количество новостей на главной странице:</label>
            <input type="text" class="b-input-text" name="maxnews"  value="<?=$settings->maxnews?>" />
            <label for="maxdocs">Количество документов на главной странице:</label>
            <input type="text" class="b-input-text" name="maxdocs" value="<?=$settings->maxdocs?>" />
            <label for="maxguest">Записей в гостевой книге на страницу:</label>
            <input type="text" class="b-input-text" name="maxguest" value="<?=$settings->maxguest?>" />
            <label for="maxguest">Файлов на странице в разделе администратора:</label>
            <input type="text" class="b-input-text" name="files_limit" value="<?=$settings->files_limit?>" />
            <label for="maxguest">Новостей на странице в разделе администратора:</label>
            <input type="text" class="b-input-text" name="news_limit" value="<?=$settings->news_limit?>" />
            <label for="maxguest">Записей гостевой книги на странице в разделе администратора:</label>
            <input type="text" class="b-input-text" name="guest_limit" value="<?=$settings->guest_limit?>" />
            <input type="submit" class="b-submit" value="Отправить" />
        </fieldset>
    </form>
</div>
<?php endif;?>
<div class="b-news-list">
    <ul>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/abiturient/">Информация для абитуриентов</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/student/">Информация для студентов</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/lecturers/">Страницы преподавателей</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/speciality/">Страница о специальностях</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/contacts/">Страница с контактами</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/publications/">Публикации</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/patents/">Патенты</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/exhibition/">Выставки</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/info/awards/">Награды</a></span></li>
    </ul>
</div>