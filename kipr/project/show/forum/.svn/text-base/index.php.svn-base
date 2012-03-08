<?php if($all_cat > 1): ?>
<div class="switchforumdiv">
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="switchforum">
                <form style="margin: 0px" action="<?=$hostname?>forum/" method="get">
                    <nobr><p class="texthelp">Выбрать другой форум</p><br />
                            <select name="id_forum">
                               <?php  foreach($cats as $category):?>
                                <option value=<?=$category['cat']->id?>><?=$category['cat']->name?></option>
                               <?php endforeach;?>
                            </select>
                            <input class="button" type="submit" value="Перейти" />
                    </nobr>
            </td>
        </tr>
    </table>
    </form>
</div>
<?php endif;?>
<?php if($all_cat > 0): ?>
<div class="images">&nbsp;</div>
<?php if($registered):?>
<p class=salutation>Вы зашли как: <?=$username?> (<a title="Созданные вами темы" href="<?=$hostname?>forum/authorthemes/<?=$registered?>/">мои темы</a>, <a title="Последние темы, в которых вы принимали участие" href="<?=$hostname?>forum/authorlastthemes/<?=$registered?>/">последние темы</a>,
    <a title="Редактирование личных данных пользователя" href="<?=$hostname?>forum/update/<?=$registered?>/">личные данные</a>)</p>
<?php else:?>
<p class="salutation">Здравствуйте, Посетитель!  </p>
<?endif;?>
<?php if(!empty($message)):?><p class="salutation"><?=$message?></p><?php endif;?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="bodydiv">
            <?=$useractions?>
            <table border=0 class="temamenu" cellspacing="1" cellpadding="0" width=100%>
                <?php foreach($cats as $category):?>
                <tr valign="top" class="trtema">
                        <td class="image">&nbsp;</td>
                        <td style='padding-left: 10px'>
                            <table class="otstup">
                                <tr>
                                    <td class="nameforum">
                                        <a href='<?=$hostname?>forum/category/<?=$category['cat']->id?>'><nobr><?=$category['cat']->name?></nobr></a>
                                    </td>
                                    <td class="menuinfo" style='padding-left: 10px'>
                                        <?=$category['cat']->logo?>
                                    </td>
                                </tr>
                            </table>
                            <?php if(!empty($category['last_themes'])):?>
                                <div class="bodydiv">
                                    <table border="0" width="100%" cellspacing="1" cellpadding="0" class="temamenu">
                                        <tr align="center" class="headertable">
                                            <td width="100px" class="headertable">&nbsp;</td>
                                            <td class="headertable"><p class="fieldnameindex">последние темы</td>
                                            <td width="210" class="headertable"><p class="fieldnameindex">последнее сообщение</td>
                                            <?php foreach($category['last_themes'] as $theme):?>
                                                <tr class="trtema">
                                                        <td widht="100px" class="trtemaheight" align="center">
                                                            <p class="nametema">
                                                             <?php if(!empty($theme['new_posts'])):?>
                                                                <b><?=$theme['posts']?> (<?=$theme['new_posts']?>)</b>
                                                                <?php else:?>
                                                                    <?=$theme['posts']?>
                                                                <?php endif;?>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p class="nametema"><a href="<?=$hostname?>forum/theme/<?=$theme['theme']->id?>/category/<?=$category['cat']->id?>/"><?=$theme['theme']->name?></a></p>
                                                        </td>
                                                        <td width="210" class="tddate">
                                                            <p class="nametema"><?=$theme['theme']->time->format('d.m.Y')?> в <?=$theme['theme']->time->format('H:i')?> от <?=$theme['theme']->last_author?></p>
                                                        </td>
                                                    </tr>
                                            <?php endforeach;?>
                                    </table>
                                </div>
                            <?php endif;?>
                <?php endforeach;?>
            </table>
        </td>
    </tr>
</table>
<?php endif;?>