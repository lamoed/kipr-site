<?php if($all_cat > 1): ?>
<div class="switchforumdiv">
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="switchforum">
                <form style="margin: 0px" action="<?=$hostname?>forum/" method="get">
                    <nobr><p class="texthelp">Выбрать другой форум<br>
                            <select type="text" name="id_forum">
                               <?php  foreach($cats as $category):?>
                                <?php if($category->id == $current_forum):?>
                                    <option selected="selected" value=<?=$category->id?>><?=$category->name?></option>
                                <?php else:?>
                                    <option value=<?=$category->id?>><?=$category->name?></option>
                                <?php endif?>
                               <?php endforeach;?>
                            </select>
                            <input class="button" type="submit" value="Перейти">
                    </nobr>
            </td>
        </tr>
    </table>
    </form>
</div>
<?php endif;?>