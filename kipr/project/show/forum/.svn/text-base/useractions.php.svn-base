<table border=0 width=100%>
    <tr valign="bottom">
        <td>
        <?php if($registered):?>
            <?php if(!empty($current_forum)):?><p class="menu"><img src="/look/pic/newtema.gif" border="0" width="20" height="15"><a title="Создать новую тему" class="menu" href="<?=$hostname?>forum/addtheme/<?=$current_forum?>">Новая&nbsp;тема</a><?php endif;?>
            <?php if(!empty($posts['addt'])):?>
                <p class=menu><img src="/look/pic/listforum.gif" border="0" width="20" height="15" alt="Показать список тем форума"/>&nbsp;<a title='Показать список тем форума' class="menu" href="<?=$hostname?>forum/category/<?=$current_forum?>">Список&nbsp;тем</a></p>
            <?php endif;?>
            <p class="menu"><img src="/look/pic/find.gif" border="0" width="20" height="15" alt="Поиск по сайту" /><a title="Поиск по сайту" class="menu" href="<?=$hostname?>forum/search/category/<?=$current_forum?>">Поиск</a></p>
            <?php if(!empty($current_forum)):?><p class="menu"><img src="/look/pic/check.gif" border="0" width="20" height="15" alt="Отметить все темы форума как прочитанные" /><a title="Отметить все темы форума как прочитанные" class="menu" href="<?=$hostname?>forum/markreaded/<?=$current_forum?>">Отметить&nbsp;всё</a></p><?php endif;?>
            <p class="menu"><img src="/look/pic/users.gif" border="0" alt="Пользователи" /><a title="Пользователи" class="menu" href="<?=$hostname?>forum/users/<?=$current_forum?>/">Пользователи</a></p>
            <p class="menu"><img src="/look/pic/enter.gif" border="0" width="20" height="15" alt="Выход" />&nbsp;<a title="Выход" class="menu" href="<?=$hostname?>forum/logout/">Выход</a></p>
        <?php else:?>
            <?php if(!empty($posts['addt'])):?>
                <p class=menu><img src="/look/pic/listforum.gif" border="0" width="20" height="15" alt="Показать список тем форума"/>&nbsp;<a title='Показать список тем форума' class="menu" href="<?=$hostname?>forum/category/<?=$current_forum?>">Список&nbsp;тем</a></p>
            <?php endif;?>
            <p class="menu"><img src="/look/pic/enterforum.gif" border="0" width="20" height="15" alt="Регистрация" />&nbsp;<a title="Зарегистрироваться на форуме" class="menu" href="<?=$hostname?>forum/register/">Регистрация</a>
            <p class="menu"><img src="/look/pic/enter.gif" border="0" width="20" height="15" alt="Вход" />&nbsp;<a title="Вход на форум" class="menu" href="<?=$hostname?>forum/login/">Вход</a>
        <?php endif;?>
        </td>
        <?php if(!empty($posts['addt'])):?>
        <td align="center" class="switchtypeforum">
            <p class="texthelp"><nobr>вид форума:</nobr><br />
            <nobr>
                <?php if($lineforum != 1):?>
                    <a title="Линейный форум (новые сообщения вниз)" href="<?=$hostname?>forum/theme/<?=$theme->id?>/category/<?=$current_forum?>/linear/1/">
                    <img width="20" height="15" border="0" alt="Линейный форум" src="/look/pic/lineforumuphide.gif"></a>
                    <img width="20" height="15" border="0" alt="Структурный форум" src="/look/pic/structforum.gif">
                <?php else:?>
                    <?php if($lineforumup == 1):?>
                         <a title="Линейный форум (новые сообщения вниз)" href="<?=$hostname?>forum/theme/<?=$theme->id?>/category/<?=$current_forum?>/linear/1/">
                        <img width="20" height="15" border="0" alt="Линейный форум" src="/look/pic/lineforumup.gif"></a>
                        <a title="Структурный форум" href="<?=$hostname?>forum/theme/<?=$theme->id?>/category/<?=$current_forum?>/linear/0/">
                        <img width="20" height="15" border="0" alt="Структурный форум" src="/look/pic/structforumhide.gif"></a>
                    <?php else:?>
                        <a title="Линейный форум (новые сообщения вниз)" href="<?=$hostname?>forum/theme/<?=$theme->id?>/category/<?=$current_forum?>/linear/1/up/1">
                        <img width="20" height="15" border="0" alt="Линейный форум (новые сообщения вниз)" src="/look/pic/lineforumdown.gif"></a>
                        <a title="Структурный форум" href="<?=$hostname?>forum/theme/<?=$theme->id?>/category/<?=$current_forum?>/linear/0/">
                        <img width="20" height="15" border="0" alt="Структурный форум" src="/look/pic/structforumhide.gif"></a>
                    <?php endif;?>
                <?php endif;?>
            </nobr>
            </p>
        </td>
        <?php endif;?>
</tr>
</table> <br />
