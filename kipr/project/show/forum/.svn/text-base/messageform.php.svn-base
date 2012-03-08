<div class="b-center">
     <?php if($errors['message']): ?><div class="b-error"><?=$errors['message'] ?></div><?php endif; ?>
    <div class="bodyform">
        <form action="<?=$hostname?>forum/postwrite/" enctype='multipart/form-data' name='form' method='post' id="postwriteform">
            <p class=texthelp>Вы отвечаете на сообщение:</p>
            <div class="blockanswer">
                <p class=texthelp>
                    ник: <em class=author><?=$author?></em><br />
                    <?=$post?>
                </p>
            </div><br />
            <div class="blockremark halfwidth">
                <p><a href=# onClick="javascript:click_link()" href=#>Цитировать</a><br /><br />
                    Используйте тэги для выделения текста:<br />
                    Жирный: <a href=# onClick="javascript:tag('[b]', '[/b]'); return false;" >[b][/b]</a><br />
                    Наклонный: <a href=# onClick="javascript:tag('[i]', '[/i]'); return false;" >[i][/i]</a><br />
                    URL: <a href=# onClick="javascript:tag('[url]', '[/url]'); return false;" >[url][/url]</a><br />
                </p>
            </div>
            <p class="fieldname">Сообщение:</p><br />
            <textarea class="validate[required] input" cols=110 rows=15 id="message" name="message"></textarea>
            <input class="button" type="submit" name="send" value="Отправить" />
            <p class=texthelp>Для вставки смайлов в текст щелкните по значку.</p>
            <div class="blockremark">
                <?=$smiles?>
            </div>
            <input type=hidden name="theme" value="<?=$theme?>" />
            <input type=hidden name="id_post" value="<?=$message_id?>" />
            <input type=hidden name="category" value="<?=$category?>" />
            <p class="linkbackbig"><a href="<?=$hostname?>forum/theme/<?=$theme?>/category/<?=$category?>">Вернуться к теме</a></p>
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
    function click_link()
    {
        document.form.message.value = document.form.message.value + '<?=$quotetext?>';
    }

    //-->
</script>