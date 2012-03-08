<div class="b-news-header"><img src="/look/pic/book.png" alt="" /><h3>Cообщения гостевой книги</h3></div>
<?php if($records): ?>
<div class="b-news-list b-guest">
    <ul>
        <?php foreach($records as $record): ?>
        <li><span class="b-left b-guest-left"><img src="/look/pic/delete.png" alt="" onmouseout="document.body.style.cursor='default'" onmouseover="document.body.style.cursor='pointer'" onclick="sure(<?=$record->id?>, 'guestbook');" /><b><?=$record->name?></b> &mdash; <?=$record->message?>...</span><span class="b-right b-guest-right"><?=$record->writetime->format('d.m.Y H:i')?></span></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>
<?=$pagelist?>