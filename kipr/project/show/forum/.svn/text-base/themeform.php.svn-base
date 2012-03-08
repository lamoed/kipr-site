<div class="b-center">
    <div class="b-news-header"><h3>Новая тема</h3></div>
    <div class=bodyform>
     <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
        <form action="<?=$hostname?>forum/addtheme/" enctype='multipart/form-data' name='form' method='post' id="themeaddform">
            <div class="blockremark halfwidth">
                <p>
                    Используйте тэги для выделения текста:<br />
                    Жирный: <a href=# onClick="javascript:tag('[b]', '[/b]'); return false;" >[b][/b]</a><br />
                    Наклонный: <a href=# onClick="javascript:tag('[i]', '[/i]'); return false;" >[i][/i]</a><br />
                    URL: <a href=# onClick="javascript:tag('[url]', '[/url]'); return false;" >[url][/url]</a><br />
                </p>
            </div>
             <?php if($errors['sub']): ?><div class="b-error"><?=$errors['sub'] ?></div><?php endif; ?>
            <p class="fieldname">Тема:</p>
            <input type="text" maxlength="150" size="74" id="sub" name="sub" class="validate[required] input" value="<?=$answer['sub']?>">
             <?php if($errors['messagefield']): ?><div class="b-error"><?=$errors['messagefield'] ?></div><?php endif; ?>
            <p class="fieldname">Сообщение:</p><br />
            <textarea class="validate[required] input" cols=110 rows=15 id="message" name="message"><?=$answer['messagefield']?></textarea>
            <input class="button" type="submit" name="send" value="Отправить" />
            <p class=texthelp>Для вставки смайлов в текст щелкните по значку.</p>
            <div class="blockremark">
                <?=$smiles?>
            </div>
            <input type=hidden name="theme" value="0" />
            <input type=hidden name="id_post" value="0" />
            <input type=hidden name="category" value="<?=$id_forum?>" />
            <p class="linkbackbig"><a href="<?=$hostname?>forum/category/<?=$id_forum?>">Вернуться</a></p>
        </form>
    </div>
</div>
<script language='JavaScript1.1' type='text/javascript'>
    <!--
    function tag(text1, text2)
    {
        if ((document.selection))
        {
            document.form.message.focus();
            document.form.document.selection.createRange().text = text1+document.form.document.selection.createRange().text+text2;
        } else if(document.forms['form'].elements['message'].selectionStart != undefined) {
            var element    = document.forms['form'].elements['message'];
            var str     = element.value;
            var start    = element.selectionStart;
            var length    = element.selectionEnd - element.selectionStart;
            element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
        } else document.form.message.value += text1+text2;
    }
    //-->
</script>
