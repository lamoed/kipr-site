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
             <?php if(!empty($posts['pst'])):?>
              <table class=readmenu border="0" width="100%" cellpadding="4" cellspacing="0" >
               <tr>
                <td class="headertable" width="70%" valign="middle">
                  <div class=nametemaread>
                  <em style="font-size: 11px">тема: </em><?=$theme->name?></div>
                 </td>
              </tr>
              </table>
              <table class=fonposts width="100%" border="0" cellspacing="1" cellpadding="0">
               <?php foreach($posts['pst'] as $post_numb => $post): ?>
                  <tr>
                      <td>
                           <?php
                            $class = $posts['addt'][$post_numb]['new'] ? "postbodynew" : "postbody";
                            $new_pic = $posts['addt'][$post_numb]['new'] ? "<img src=\"/look/pic/new_mess.png\" alt=\"Новое сообщение\" />" : "&nbsp;";
                          ?>
                          <table border=0 width=100%  class="<?=$class?>"  cellpadding=3 cellspacing=0>
                              <tr>
                                  <td width='<?=$posts['addt'][$post_numb]['indent']?>%'><?=$new_pic?></td>
                                  <td class=infopost>
                                      <?php
                                        $userid = $posts['addt'][$post_numb]['user'];
                                        $post_author = ($posts['users'][$userid]->statususer == 'admin') ? "<span class='admin'>{$post->author}</span>" : $post->author;
                                      ?>
                                      <div style='float: left'>&nbsp;автор: <a class="authorreg" href="<?=$hostname?>forum/userinfo/<?=$post->id_author?>/category/<?=$theme->id_forum?>/"><?=$post_author?></a>&nbsp;&nbsp;&nbsp;(<?=$post->time->format('d.m.Y')?> в <?=$post->time->format('H:i')?>)</div>
                                  </td>

                                  <td class=infopost width=50>&nbsp;</td>
                              </tr>
                              <?php if(!empty($posts['addt'][$post_numb]['parent_post'])):?>
                              <tr>
                                  <td width='<?=$posts['addt'][$post_numb]['indent']?>%'>&nbsp;</td>
                                  <td colspan=2 class=toauthor>&nbsp;<b>to: <?=$posts['addt'][$post_numb]['parent_post']->author?></b>
                                      &nbsp;&nbsp;(<?=$posts['addt'][$post_numb]['parent_post']->time->format('d.m.Y')?> в <?=$posts['addt'][$post_numb]['parent_post']->time->format('H:i')?>)
                                  </td>
                              </tr>
                              <?php endif;?>
                              <tr valign=top>
                                  <?php 
                                      $userid = $posts['addt'][$post_numb]['user'];
                                      $photo = !empty($posts['users'][$userid]->photo) ? $posts['users'][$userid]->photo : "/look/pic/default_forum.png";
                                   ?>
                                  <td width='<?=$posts['addt'][$post_numb]['indent']?>%'><img src="<?=$photo?>" alt="<?=$post->author?>" title="<?=$post->author?>" /></td>
                                  <td><p class=posttext><?=$posts['addt'][$post_numb]['text']?></p></td>
                                  <td></td>
                              </tr>
                              <tr>
                                  <td width='<?=$posts['addt'][$post_numb]['indent']?>%'>&nbsp;</td>
                                  <?php if($registered && $theme->hide != 'lock' && $post->hide != 'lock'):?>
                                  <td class=postmenu><img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="Ответить"/><a href="<?=$hostname?>forum/addmessage/<?=$post->id?>/theme/<?=$theme->id?>/category/<?=$theme->id_forum?>">Ответить</a></td>
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
