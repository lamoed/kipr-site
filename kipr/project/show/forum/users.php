<div class="b-center">
    <div class="b-news-header"><h3>Пользователи форума</h3></div>
    <div class="bodyform">
        <p class=linkbackbig><a href="<?=$hostname?>forum/category/<?=$category?>">Вернуться назад</a></p>
        <?php if(!empty($users)):?>
        <table class="tablen" width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="silver">
            <tr>
                <td class="tableheadern">
                    <p class="fieldname">Участник&nbsp;форума</p>
                </td>
                <td class="tableheadern">
                    <p class="fieldname">Количество&nbsp;сообщений</p>
                </td>
                <td class="tableheadern">
                    <p class="fieldname">Последнее&nbsp;посещение</p>
                </td>
            </tr>
            <?php foreach($users as $key => $user):?>
            <tr class="trtablen">
                <td>
                    <?php
                        $author = ($user->statususer == 'admin') ? "<span class='admin'>{$user->name}</span>" : $user->name;
                    ?>
                    <p class="authorreg"><nobr><a class="authorreg" href="<?=$hostname?>forum/userinfo/<?=$user->id?>/category/<?=$category?>"><?=$author?></a></nobr></p>
                </td>
                <td>
                    <p class="texthelp" align="center"><?=$user->themes?></p>
                </td>
                <td>
                    <p class="texthelp" align="center"><?=$user->time->format('d.m.Y')?> в <?=$user->time->format('H:i')?></td>
                <td>
            </tr>
            <?php endforeach;?>
        </table>
       <?php if($pagelist):?><p class="texthelp"><?=$pagelist?>&nbsp;</p><?php endif;?>
       <?php else:?>
        <p class="zagtext">На форуме отсутствуют пользователи</p>
       <?php endif;?>
    </div>
</div>