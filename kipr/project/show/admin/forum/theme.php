<?php if(!empty($categories)):?>
<table width=100% class="table" border="0" cellpadding="0" cellspacing="0">
          <tr class="header" align="center" valign="middle">
              <?php foreach ($categories as $key => $cat):?>
              <td>
                  <a <?=($cat->id_forum == $selected)?"class='cat-selected'":""?> href="<?=$hostname?>admin/forum/themes/<?=$cat->id_forum?>"><?=$cat->name?></a>
              </td>
              <?php endforeach;?>
          </tr>
      </table><br /><br />
<?php endif;?>
<?php if(!empty($posts['addt'])):?>
<table class="forumposts" border="0" width="100%" cellpadding="0" cellspacing="1">
    
      <?php foreach($posts['addt'] as $post_numb => $post): ?>
      <tr>
          <td>
              <?php
                $class = $post['new'] ? "postbodynew" : "postbody";
                $new_pic = $post['new'] ? "<img src=\"/look/pic/new_mess.png\" alt=\"Новое сообщение\" />" : "&nbsp;";
              ?>
              <table border=0 width=100%  class="<?=$class?>"  cellpadding="3" cellspacing=0>
                  <tr>
                      <td width='<?=$post['indent']?>%'><?=$new_pic?></td>
                      <td class="infopost">
                          <div style='float: left'>&nbsp;автор: <a class="authorreg" href="<?=$hostname?>admin/forum/user/view/<?=$posts['pst'][$post_numb]->id_author?>/"><?=$posts['pst'][$post_numb]->author?></a>&nbsp;&nbsp;&nbsp;(<?=$posts['pst'][$post_numb]->time->format('d.m.Y')?> в <?=$posts['pst'][$post_numb]->time->format('H:i')?>)</div>
                      </td>
                      <td class="infopost" width="50">&nbsp;</td>
                 </tr>
                <tr valign="top">
                    <?php
                        $userid = $post['user'];
                        $photo = !empty($posts['users'][$userid]->photo) ? $posts['users'][$userid]->photo : "/look/pic/default_forum.png";
                    ?>
                    <td width='<?=$post['indent']?>%'><img src="<?=$photo?>" alt="<?=$posts['pst'][$post_numb]->author?>" title="<?=$posts['pst'][$post_numb]->author?>" /></td>
                    <td><p class="posttext"><?=$post['text']?></p></td>
                    <td></td>
                </tr>
                <tr>
                    <td width='<?=$post['indent']?>%'>&nbsp;</td>
                        <td class="postmenu">
                            <?php
                                $posthide = $posts['pst'][$post_numb]->hide;
                                $show = "Отобразить";
                                $hide = "Скрыть";
                                $lock = "Закрыть";
                                $$posthide = "<b>" . $$posthide . "</b>";
                            ?>
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="Сделать сообщение доступным для посетителей">
                            <a href="<?=$hostname?>admin/forum/post/show/<?=$posts['pst'][$post_numb]->id_post?>/" title='Сделать сообщение доступным для посетителей'><?=$show?></a>&nbsp;&nbsp;&nbsp;
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="Сделать сообщение недоступной для посетителей">
                            <a href="<?=$hostname?>admin/forum/post/hide/<?=$posts['pst'][$post_numb]->id_post?>/" title='Сделать сообщение недоступной для посетителей'><?=$hide?></a>&nbsp;&nbsp;&nbsp;
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="Запретить ответ на данное сообщение">
                            <a href="<?=$hostname?>admin/forum/post/close/<?=$posts['pst'][$post_numb]->id_post?>/" title='Запретить ответ на данное сообщение'><?=$lock?></a>&nbsp;&nbsp;&nbsp;
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="Править">
                            <a href="<?=$hostname?>admin/forum/post/edit/<?=$posts['pst'][$post_numb]->id_post?>/theme/<?=$posts['pst'][$post_numb]->id_theme?>/">Править</a>
                        </td>
                   <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="<?=$post['indent']?>%">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
              </table>
          </td>
        </tr>
        <?php endforeach;?>
</table>
<?php else:?>
    <h2 align="center">В теме нет сообщений</h2>
<?php endif;?>