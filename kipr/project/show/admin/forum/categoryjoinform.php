<?php if(!empty($categories)):?>
<form name="categoryjoin" action="<?=$hostname?>admin/forum/category/join/<?=isset($answer['id_forum'])?$answer['id_forum']:$category->id_forum?>" method="post">
    <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <table>
        <tr>
            <?php if($errors['forum']): ?><div class="b-error"><?=$errors['forum'] ?></div><?php endif; ?>
            <td width=150 class="field" valign="top">Переместить раздел в :</td>
            <td  class="field" valign="top">
                <select name="forum">
                   <?php foreach ($categories as $key => $value):?>
                    <option value="<?=$value->id_forum?>"><?=$value->name?></option>
                   <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td  class="field"></td>
            <td  class="field">
                <br /><input class="button" type="submit" value="Объединить">
            </td>
        </tr>
    </table>
</form>
<?php else:?>
    <h2 align="center">На форуме отсутствуют разделы, с которыми можно бы было объединить данный раздел</h2>
<?php endif;?>