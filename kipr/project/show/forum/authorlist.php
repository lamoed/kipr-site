<div class="b-center">
    <div class="b-news-header"><h3>Список последних тем</h3></div>
    <div class=bodyform>
        <p class="linkbackbig"><a href="<?=$hostname?>forum/category/<?=$category?>">Вернуться</a></p>
        <?php if(empty($themes)):?>
            <p class="zagtext">Вами не было написано сообщений</p>
        <?php else:?>
        <p class="zagtext">Результаты:</p>
        <table class="srchtable" border="0" width="100%" cellpadding="4" cellspacing="1" >
            <tr class="tableheadern" align="center">
                <td class="tableheadern">
                    <p class="fieldnameindex"><nobr>Кол-во</nobr> сообщ.</p>
                </td>
                <td class="tableheadern">
                    <p class="fieldnameindex">Название темы</p>
                </td>
                <td class="tableheadern">
                    <p class="fieldnameindex">Автор</p>
                </td>
                <td class="tableheadern">
                    <p class="fieldnameindex">Последнее сообщение</p>
                </td>
            </tr>
            <?php foreach($themes as $key => $theme):?>
            <tr class="trtablen">
                <td class="trtemaheight" align="center">
                    <p class=nametema><nobr><?=$theme['posts']?></nobr></p>
                </td>
                <td>
                    <p><a target='_blank' href='<?=$hostname?>forum/theme/<?=$theme['theme']->id_theme?>/category/<?=$theme['theme']->id_forum?>'><?=$theme['theme']->name?></a></p>
                </td>
                <td>
                    <?php
                        $user = ($theme['theme']->authorstat == 'admin') ? "<span class='admin'>{$theme['theme']->author}</span>" : $theme['theme']->author;
                    ?>
                    <p class="authorreg"><a class="authorreg" href='<?=$hostname?>forum/userinfo/<?=$theme['theme']->id_author?>/category/<?=$category?>'><?=$user?></a>
                </td>
                <td>
                    <p class=texthelp><?=$theme['theme']->time->format('d.m.Y')?> в <?=$theme['theme']->time->format('H:i')?></p>
                </td>
            </tr>
           <?php endforeach;?>
        </table>
        <?php endif;?>
    </div>
</div>