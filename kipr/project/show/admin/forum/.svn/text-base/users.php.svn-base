<?php if(!empty($users)):?>
 <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
        <td align="center">Участник&nbsp;форума</td>
        <td align="center">Количество&nbsp;сообщений</td>
        <td align="center">Последнее&nbsp;посещение</td>
        <td colspan="2" align="center">Действия</td>
        <td colspan="3" align="center">Статус</td>
    </tr>
    <?php foreach($users as $key => $user):?>
    <tr>
        <td><a href="<?=$hostname?>admin/forum/user/view/<?=$user->id_author?>/page/<?=$page?>"><?=$user->name?></a></td>
        <td align="center"><?=$user->themes?></td>
        <td align="center"><?=$user->time->format('d.m.Y')?> в <?=$user->time->format('H:i')?></td>
        <td align="center"><a href="<?=$hostname?>admin/forum/user/edit/<?=$user->id_author?>/page/<?=$page?>">Редактировать</a></td>
        <td align="center"><a href="#" onclick="sure(<?=$user->id_author?>, 'user');">Удалить</a></td>
        <td align="center" <?=($user->statususer == "")?"class='header'":""?>><a href="<?=$hostname?>admin/forum/user/ordinary/<?=$user->id_author?>/" title='Назначить посетителю статус обычного участника форума'>Посетитель</a></td>
        <td align="center" <?=($user->statususer == "admin")?"class='header'":""?>><a href="<?=$hostname?>admin/forum/user/administrator/<?=$user->id_author?>/" title='Назначить посетителю статус администратора форума'>Администратор</a></td>
    </tr>
    <?php endforeach;?>
    <?php if(!empty($pagelist)):?>
        <tr>
            <td class="bottomtablen" colspan="7"><span class="main_txt">&nbsp;<?=$pagelist?>&nbsp;</span></td>
        </tr>
    <?php endif;?>
</table>
 <?php else:?>
    <h2 align="center">На форуме отсутствуют пользователи</h2>
<?php endif;?>