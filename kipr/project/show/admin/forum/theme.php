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
                $new_pic = $post['new'] ? "<img src=\"/look/pic/new_mess.png\" alt=\"����� ���������\" />" : "&nbsp;";
              ?>
              <table border=0 width=100%  class="<?=$class?>"  cellpadding="3" cellspacing=0>
                  <tr>
                      <td width='<?=$post['indent']?>%'><?=$new_pic?></td>
                      <td class="infopost">
                          <div style='float: left'>&nbsp;�����: <a class="authorreg" href="<?=$hostname?>admin/forum/user/view/<?=$posts['pst'][$post_numb]->id_author?>/"><?=$posts['pst'][$post_numb]->author?></a>&nbsp;&nbsp;&nbsp;(<?=$posts['pst'][$post_numb]->time->format('d.m.Y')?> � <?=$posts['pst'][$post_numb]->time->format('H:i')?>)</div>
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
                                $show = "����������";
                                $hide = "������";
                                $lock = "�������";
                                $$posthide = "<b>" . $$posthide . "</b>";
                            ?>
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="������� ��������� ��������� ��� �����������">
                            <a href="<?=$hostname?>admin/forum/post/show/<?=$posts['pst'][$post_numb]->id_post?>/" title='������� ��������� ��������� ��� �����������'><?=$show?></a>&nbsp;&nbsp;&nbsp;
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="������� ��������� ����������� ��� �����������">
                            <a href="<?=$hostname?>admin/forum/post/hide/<?=$posts['pst'][$post_numb]->id_post?>/" title='������� ��������� ����������� ��� �����������'><?=$hide?></a>&nbsp;&nbsp;&nbsp;
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="��������� ����� �� ������ ���������">
                            <a href="<?=$hostname?>admin/forum/post/close/<?=$posts['pst'][$post_numb]->id_post?>/" title='��������� ����� �� ������ ���������'><?=$lock?></a>&nbsp;&nbsp;&nbsp;
                            <img src='/look/pic/pen.gif' border='0' width='20' height='15' alt="�������">
                            <a href="<?=$hostname?>admin/forum/post/edit/<?=$posts['pst'][$post_numb]->id_post?>/theme/<?=$posts['pst'][$post_numb]->id_theme?>/">�������</a>
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
    <h2 align="center">� ���� ��� ���������</h2>
<?php endif;?>