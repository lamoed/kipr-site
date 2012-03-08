<div class="b-center">
    <div class="b-news-header"><h3>Информация о пользователе</h3></div>
    <div class="bodyform">
        <?php if(empty($author)):?>
            <div class="b-error"><?=$message?></div>
        <?php else:?>
        <table>
            <tr>
                <td>
                    <p class="fieldname">Имя</p>
                </td>
                <td>
                    <?php
                        $user = ($author->statususer == 'admin') ? "<span class='admin'>{$author->name}</span>" : $author->name;
                    ?>
                    <p class="authortext"><?=$user?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">e-mail</p>
                </td>
                <td>
                    <p class="text"><?php if(!empty($author->email)):?><a href="mailto:<?=$author->email?>">написать письмо</a><?php endif;?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">URL</td>
                <td>
                    <p class="text"><?php if(!empty($author->url)):?><a target="_blank" href="<?=$author->url?>"><?=$author->url?><?php endif;?></a>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">ICQ
                </td>
                <td><p class="text"><?php if(!empty($author->icq)):?><a href='http://www.icq.com/scripts/search.dll?to=<?=$author->icq?>' title='Добавить в мой контакт лист' target='_blank'>
                            <img src='http://wwp.icq.com/scripts/online.dll?icq=<?=$author->icq?>&img=5' width="18" height="18" border="0" alt="icq" /><?=$author->icq?></a><?php endif;?></p></td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">О себе</p>
                </td>
                <td>
                    <p><?=$about?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">Порядковый номер</p>
                </td>
                <td>
                    <p class="texthelp"><?=$author->id_author?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">Количество сообщений</p>
                </td>
                <td>
                    <p class=texthelp><?=$author->themes?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">Последнее посещение</p>
                </td>
                <td>
                    <p class=texthelp><?=$author->time->format('d.m.Y')?> в <?=$author->time->format('H:i')?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fieldname">Все темы автора</p>
                </td>
                <td>
                    <p class=texthelp>
                        <a href="<?=$hostname?>forum/authorthemes/<?=$author->id_author?>/category/<?=$category?>">Показать</a>
                    </p>
                </td>
            </tr>
        </table>
        <?php endif;?>
         <p class="linkbackbig"><a href="<?=$hostname?>forum/category/<?=$category?>">Вернуться назад</a></p>
    </div>
</div>