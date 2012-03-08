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
      <?php if(!empty($themes)):?>
      <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr class="header">
            <td class="headtable" align="center">Cообщений</td>
            <td class="headtable" align="center">Название темы</td>
            <td width="70" class="headtable" align=center>Автор</td>
            <td class=headtable align=center colspan=4>Действия</td>
          </tr>
          <?php foreach($themes as $key => $theme): ?>
                <tr>
                    <td align="center" width="50"><?=$posts['posts'][$key]?></td>
                    <td>
                        <a href="<?=$hostname?>admin/forum/theme/view/<?=$theme->id_theme?>"><?=$theme->name?></a>
                    </td>
                    <td>
                        <a href="<?=$hostname?>admin/forum/user/view/<?=$theme->id_author?>"><?=$theme->author?></a>
                    </td>
                    <td width="100" align="center">
                        <a href="<?=$hostname?>admin/forum/theme/edit/<?=$theme->id_theme?>">Редактировать</a>
                    </td>
                    <td <?=($theme->hide == "show")?"class='header'":""?> width="100" align='center' title='Сделать тему доступной для просмотра'>
                        <a href="<?=$hostname?>admin/forum/theme/show/<?=$theme->id_theme?>">Доступно</a>
                    </td>
                    <td <?=($theme->hide == "hide")?"class='header'":""?> width="100" align="center" title='Сделать тему недоступной для просмотра'>
                        <a href="<?=$hostname?>admin/forum/theme/hide/<?=$theme->id_theme?>">Скрыто</a>
                    </td>
                    <td <?=($theme->hide == "lock")?"class='header'":""?> width="100" align="center" title='Запретить добавление новых сообщений'>
                        <a href="<?=$hostname?>admin/forum/theme/close/<?=$theme->id_theme?>">Закрыто</a>
                    </td>
                </tr>
            <?php endforeach;?>
               <?php if(!empty($pagelist)):?>
                    <tr>
                        <td class="bottomtablen" colspan="7"><span class="main_txt">&nbsp;<?=$pagelist?>&nbsp;</span></td>
                    </tr>
                <?php endif;?>
        </table>
        <?php else:?>
            <h2 align="center">В данном разделе отсутствуют темы.</h2>
        <?php endif;?>
<?php else:?>
      <h2 align="center">На форуме отсутствуют разделы.</h2>
<?php endif;?>