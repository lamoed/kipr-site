<?=$topmenu?>
<?php if($all_cat > 0): ?>
<div class="images">&nbsp;</div>
<?php if($registered):?>
<p class=salutation>�� ����� ���: <?=$username?> (<a title="��������� ���� ����" href="<?=$hostname?>forum/authorthemes/<?=$registered?>/category/<?=$current_forum?>/">��� ����</a>, <a title="��������� ����, � ������� �� ��������� �������" href="<?=$hostname?>forum/authorlastthemes/<?=$registered?>/category/<?=$current_forum?>/">��������� ����</a>
    , <a title="�������������� ������ ������ ������������" href="<?=$hostname?>forum/update/<?=$registered?>/category/<?=$current_forum?>/">������ ������</a>)</p>
<?php else:?>
<p class=salutation>������������, ����������!  </p>
<?endif;?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class=bodydiv>
            <?=$useractions?>
            <?php if(count($themes) > 0):?>
            <table border=0 class=temamenu cellspacing="1" cellpadding="0" width=100% >
                <tr class="headertable" align="center">
                  <td class="headertable" width=30px><p class=fieldnameindex>&nbsp;</p></td>
                  <td class="headertable"><p class=fieldnameindex>�������� ����</p></td>
                  <td class="headertable"><p class=fieldnameindex>�����</p></td>
                  <td colspan=2 width=25% class="headertable" ><p class=fieldnameindex>��������� ��������� � �����</p></td>
                 </tr>
              <?php foreach($themes as $key => $theme):?>
                  <tr <?=($theme->hide=='lock')?"class='hiddenrow'":""?> class="trtema">
                      <td class="trtemaheight" align="center">
                          <p class="namenewtema"><?=$posts[$key]?><?php if(!empty($new_posts[$key])):?>(<?=$new_posts[$key]?>)<?php endif;?></nobr></p>
                      </td>
                       <td>
                          <p class="namenewtema"><a class="namenewtema" title='' href="<?=$hostname?>forum/theme/<?=$theme->id?>/category/<?=$current_forum?>/"><?=$theme->name?></a></p>
                       </td>
                       <td>
                           <?php 
                               $author = ($theme->authorstat == 'admin') ? "<span class='admin'>{$theme->author}</span>" : $theme->author;
                               $last_author = ($theme->lastauthorstat == 'admin') ? "<span class='admin'>{$theme->last_author}</span>" : $theme->last_author;
                           ?>
                           <p class="author"><a class="authorreg" href="<?=$hostname?>forum/userinfo/<?=$theme->id_author?>/category/<?=$current_forum?>/"><?=$author?></a>
                       </td>
                       <td>
                           <p class="tddate"><nobr><?=$theme->time->format('d.m.Y')?> � <?=$theme->time->format('H:i')?></nobr></p></td>
                       <td>
                           <p class="author"><a class="authorreg" href="<?=$hostname?>forum/userinfo/<?=$theme->id_last_author?>/category/<?=$current_forum?>/"><nobr><?=$last_author?></nobr></a></p>
                       </td>
                 </tr>
             <?php endforeach;?>
            <tr>
                <td class=bottomtabletema colspan=5>
                    <div class=leftblock><p class=texthelp><?=$pagelist?>&nbsp;&nbsp;</p></div>
                </td>
            </tr>
            </table>
            <?php else:?>
                <h2 align="center">����, ��������� ��� ���������, �����������.</h2>
            <?php endif;?>
        </td>
    </tr>
</table>
<?php endif;?>