<div class="b-center">
    <div class="b-news-header"><h3>Поиск по форуму</h3></div>
    <div class=bodyform>
        <p class=linkbackbig><a href="<?=$hostname?>forum/category/<?=$category?>">Вернуться</a></p>
        <div class=blockremark><p class=texthelp align=left>Введите ключевые слова для поиска,
                настройте параметры и нажмите кнопку "Найти".<br>
                Логика "ИЛИ" означает, что в результатах поиска будут те темы, где встречается хотя бы одно
                из введенных Вами слов. Логика "И" означает, что будут найдены только те сообщения, где
                встречаются все введенные Вами слова одновременно.<br>
                Ключевое слово необязательно набирать полностью, т.е. если вы ищете "сотовый телефон", для
                поиска этой комбинации можно ввести только часть слов "сотов телеф", это обеспечит поиск
                фраз "сотовым телефоном", "сотовыми телефонами" и т.п.<br>
                Искомые слова должны содержать четыре или более символов, т.е. слова "sms", "WAP", "код"
                обнаружить не удастся.
        </div>
        <form enctype="multipart/form-data" action="<?=$hostname?>forum/search/" method="post" id="searchform">
            <fieldset>
                <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
                <?php
                    $cat_value = !empty($category) ? $category : $answer['category'];
                ?>
                <input type="hidden" name="category" value=<?=$cat_value?> />
                <table border="0">
                <tr>
                      <?php if($errors['words']): ?><div class="b-error"><?=$errors['words'] ?></div><?php endif; ?>
                  <td>
                      <p class="fieldname">Ключевые слова</p>
                  </td>
                  <td>
                      <input class="validate[required] input" type="text" id="words" name="words" size="60" maxlength="200" value="<?=$answer['words']?>" />
                  </td>
                </tr>
                <tr>
                     <?php if($errors['numberthemes']): ?><div class="b-error"><?=$errors['numberthemes'] ?></div><?php endif; ?>
                  <td>
                      <p class="fieldname">Количество <br>выводимых тем</p>
                  </td>
                  <td>
                      <input class="validate[required,custom[onlyNumber]] input" type="text" id="numberthemes" name="numberthemes" size="3" maxlength="10" value="<?php if(empty($answer['numberthemes'])) echo "30"; else echo $answer['numberthemes']; ?>">
                  </td>
                </tr>
                <tr>
                      <?php if($errors['srchwhere']): ?><div class="b-error"><?=$errors['srchwhere'] ?></div><?php endif; ?>
                  <td>
                      <p class="fieldname"><nobr>Искать в...</nobr>
                   </td>
                    <td>
                      <select class="input" type="text" name="srchwhere">
                         <option value="1" <?php if($answer['srchwhere'] == 1) echo "selected"; ?>>в названиях тем</option>
                         <option value="2" <?php if($answer['srchwhere'] == 2) echo "selected"; ?>>в сообщениях</option>
                      </select>
                   </td>
                </tr>
                <tr>
                      <?php if($errors['id_forum']): ?><div class="b-error"><?=$errors['id_forum'] ?></div><?php endif; ?>
                  <td>
                      <p class="fieldname"><nobr>Искать в форуме...</nobr></p>
                </td>
                    <td>
                      <select class="input" type="text" name="id_forum">
                          <option value="0">любом</option>
                          <?php if(!empty($cats)):
                              foreach($cats as $key => $cat):
                                  $selected = ($answer['id_forum'] == $cat->id_forum) ? "selected = \"selected\"" : "";
                          ?>
                            <option value="<?=$cat->id?>" <?=$selected?>><?=$cat->name?></option>
                          <?php
                            endforeach;
                            endif?>
                      </select>
                   </td>
                </tr>
                <tr>
                          <?php if($errors['logic']): ?><div class="b-error"><?=$errors['logic'] ?></div><?php endif; ?>
                  <td>
                      <p class="fieldname">Логика</p>
                  </td>
                  <td>
                      <p>
                          <input name="logic" type="radio" value=1 <?php if($answer['logic'] == 1) echo "checked"; ?> />И&nbsp;&nbsp;&nbsp;
                          <input name="logic" type="radio" value=0 <?php if($answer['logic'] == 0) echo "checked"; ?> />ИЛИ
                      </p>
                  </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input class="button" type="submit" name="send" value="Найти" /></td>
                </tr>
                </table>
            </fieldset>
        </form>
        <?php if(!empty($themes)):?>
        <p class="zagtext">Результаты:</p>
        <table class=srchtable border="0" width="100%" cellpadding="4" cellspacing="1" >
            <tr class="tableheadern" align="center">
                <td class="tableheadern">
                    <p class=fieldnameindex><nobr>Кол-во</nobr> сообщ.</p>
                </td>
                <td class="tableheadern">
                    <p class=fieldnameindex>Название темы</p>
                </td>
                <td class="tableheadern">
                    <p class=fieldnameindex>Автор</p>
                </td>
                <td class="tableheadern">
                    <p class=fieldnameindex>Последнее сообщение</p>
                </td>
            </tr>
            <?php foreach($themes as $theme):?>
            <tr class="trtablen">
                <td class=trtemaheight align=center>
                    <p class=nametema><nobr><a class=nametema href="<?=$hostname?>forum/theme/<?=$theme['theme']->id_theme?>/category/<?=$theme['theme']->id_forum?>"><?=$theme['posts']?></nobr></p>
                </td>
                <td>
                    <p><a target='_blank' href="<?=$hostname?>forum/theme/<?=$theme['theme']->id_theme?>/category/<?=$theme['theme']->id_forum?>"><?=$theme['theme']->name?></a></p>
                </td>
                <td>
                     <?php
                        $user = ($theme['theme']->authorstat == 'admin') ? "<span class='admin'>{$theme['theme']->author}</span>" : $theme['theme']->author;
                    ?>
                    <p class="authorreg"><a class="authorreg" href="<?=$hostname?>forum/userinfo/<?=$theme['theme']->id_author?>/category/<?=$category?>"><?=$user?></a></p>
                </td>
                <td>
                    <p class="texthelp"><?=$theme['theme']->time->format('d.m.Y')?> в <?=$theme['theme']->time->format('H:i')?></p>
                </td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td class="bottomtablen" colspan="4">
                    <p class="texthelp"><?=$pagelist?></p>
                </td>
            </tr>
        </table>
        <?php else:?>
        <p class="result"><?=$error_message?></p>
        <?php endif;?>
    </div>
</div>