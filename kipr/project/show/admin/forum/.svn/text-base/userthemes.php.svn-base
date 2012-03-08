<?php if(empty($themes)):?>
    <h2 align="center">Пользователем не создавались темы</h2>
<?php else:?>
<table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class='header'>
        <td align="center">Кол-во сообщ.</td>
        <td align="center">Название темы</td>
        <td align="center">Последнее сообщение</td>
    </tr>
    <?php foreach($themes as $key => $theme):?>
    <tr>
        <td align="center"><?=$theme['posts']?></td>
        <td><a href='<?=$hostname?>admin/forum/theme/view/<?=$theme['theme']->id_theme?>/'><?=$theme['theme']->name?></a></td>
        <td align="center"><?=$theme['theme']->time->format('d.m.Y')?> в <?=$theme['theme']->time->format('H:i')?></td>
    </tr>
    <?php endforeach;?>
    <?php if(!empty($pagelist)):?>
        <tr>
            <td class="bottomtablen" colspan="7"><span class="main_txt">&nbsp;<?=$pagelist?>&nbsp;</span></td>
        </tr>
    <?php endif;?>
</table>
<?php endif;?>