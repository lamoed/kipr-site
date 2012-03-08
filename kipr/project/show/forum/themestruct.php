<?=$topmenu?>
<?php if($all_cat > 0): ?>
<div class=images>&nbsp;</div>  
<?php if($registered):?>
<p class=salutation>Вы зашли как: <?=$username?> (<a title="Созданные вами темы" href="<?=$hostname?>forum/authorthemes/<?=$registered?>/category/<?=$theme->id_forum?>/">мои темы</a>, <a title="Последние темы, в которых вы принимали участие" href="<?=$hostname?>forum/authorlastthemes/<?=$registered?>/category/<?=$theme->id_forum?>/">последние темы</a>
    , <a title="Редактирование личных данных пользователя" href="<?=$hostname?>forum/update/<?=$registered?>/category/<?=$theme->id_forum?>/">личные данные</a>)</p>
<?php else:?>
<p class=salutation>Здравствуйте, Посетитель!  </p>
<?endif;?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class=bodydiv>
             <?=$useractions?>
            <?php if(!empty($posts['addt'])):?>
              <table class=readmenu border="0" width="100%" cellpadding="4" cellspacing="0" >
               <tr>
                <td class="headertable" width="70%" valign="middle">
                  <div class=nametemaread>
                  <em style="font-size: 11px">тема: </em><?=$theme->name?></div>
                 </td>
              </tr>
              </table>
              <table class=fonposts width="100%" border="0" cellspacing="1" cellpadding="0">
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
                                  <td class=infopost>
                                      <?php
                                        $userid = $post['user'];
                                        $post_author = ($posts['users'][$userid]->statususer == 'admin') ? "<span class='admin'>{$posts['pst'][$post_numb]->author}</span>" : $posts['pst'][$post_numb]->author;
                                      ?>
                                      <div style='float: left'>&nbsp;автор: <a class="authorreg" href="<?=$hostname?>forum/userinfo/<?=$posts['pst'][$post_numb]->id_author?>/category/<?=$theme->id_forum?>/"><?=$post_author?></a>&nbsp;&nbsp;&nbsp;(<?=$posts['pst'][$post_numb]->time->format('d.m.Y')?> в <?=$posts['pst'][$post_numb]->time->format('H:i')?>)</div>
                                  </td>
                                  <td class=infopost width=50>&nbsp;</td>
                             </tr>
                            <tr valign=top>
                                <?php
                                    $userid = $post['user'];
                                    $photo = !empty($posts['users'][$userid]->photo) ? $posts['users'][$userid]->photo : "/look/pic/default_forum.png";
                                ?>
                                <td width='<?=$post['indent']?>%'><img src="<?=$photo?>" alt="<?=$posts['pst'][$post_numb]->author?>" title="<?=$posts['pst'][$post_numb]->author?>" /></td>
                                <td><p class=posttext><?=$post['text']?></p></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width='<?=$posts['indent']?>%'>&nbsp;</td>
                                <?php if($registered && $theme->hide != 'lock' && $posts['pst'][$post_numb]->hide != 'lock'):?>
                                <td class=postmenu>
                                    <img src='/look/pic/pen.gif' border='0' width='20' height='15'><a href="<?=$hostname?>forum/addmessage/<?=$posts['pst'][$post_numb]->id?>/theme/<?=$theme->id?>/category/<?=$theme->id_forum?>">Ответить</a>
                                </td>
                                <?php endif;?>
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
         </td>
    </tr>
</table>
<?php endif;?>
