<a href="<?=$hostname?>admin/forum/category/add/" title="Добавить новый форум">Добавить новый форум</a><br /><br />
<?php if(!empty($categories)):?>
<table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align="center" width="50">Поз.</td>
      <td align="center">Название форума</td>
      <td align="center">Краткое описание</td>
      <td align="center">Действия</td>
    </tr>
    <?php foreach($categories as $key => $category):?>
    <tr <?php if($category->hide == 'hide') echo "class='hiddenrow'";?>>
     <td align="center"><?=$category->pos?></td>
     <td>
         <a href="<?=$hostname?>admin/forum/category/edit/<?=$category->id?>"><?=$category->name?></a>
     </td>
     <td><?=$category->logo?></td>
     <td align="center">
       <?php if($category->hide == 'hide'):?>
       <a href="<?=$hostname?>admin/forum/category/show/<?=$category->id?>" title="Сделать раздел видимым пользователям">Отобразить</a><br />
       <?php else:?>
       <a href="<?=$hostname?>admin/forum/category/hide/<?=$category->id?>" title="Сделать раздел невидимым пользователям">Скрыть</a><br />
       <?php endif;?>
       <a href="#" onclick="sure(<?=$category->id?>, 'category');"  title='Удалить раздел и все его сообщения'>Удалить</a><br />
       <a href="<?=$hostname?>admin/forum/category/edit/<?=$category->id?>" title="Внести исправления в название, правила и заглавную фразу раздела">Редактировать</a><br />
       <a href="<?=$hostname?>admin/forum/category/join/<?=$category->id?>" title="Переместить все сообщения раздела в другой раздел форума">Объединить</a></td>
    </tr>
    <?php endforeach;?>
</table>
<?php else:?>
<h2 align="center">На форуме отсутствуют разделы.</h2>
<?php endif;?>