<a href="<?=$hostname?>admin/forum/category/add/" title="�������� ����� �����">�������� ����� �����</a><br /><br />
<?php if(!empty($categories)):?>
<table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="header">
      <td align="center" width="50">���.</td>
      <td align="center">�������� ������</td>
      <td align="center">������� ��������</td>
      <td align="center">��������</td>
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
       <a href="<?=$hostname?>admin/forum/category/show/<?=$category->id?>" title="������� ������ ������� �������������">����������</a><br />
       <?php else:?>
       <a href="<?=$hostname?>admin/forum/category/hide/<?=$category->id?>" title="������� ������ ��������� �������������">������</a><br />
       <?php endif;?>
       <a href="#" onclick="sure(<?=$category->id?>, 'category');"  title='������� ������ � ��� ��� ���������'>�������</a><br />
       <a href="<?=$hostname?>admin/forum/category/edit/<?=$category->id?>" title="������ ����������� � ��������, ������� � ��������� ����� �������">�������������</a><br />
       <a href="<?=$hostname?>admin/forum/category/join/<?=$category->id?>" title="����������� ��� ��������� ������� � ������ ������ ������">����������</a></td>
    </tr>
    <?php endforeach;?>
</table>
<?php else:?>
<h2 align="center">�� ������ ����������� �������.</h2>
<?php endif;?>