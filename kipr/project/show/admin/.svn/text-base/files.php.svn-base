<div class="b-news-header"><img src="/look/pic/book.png" alt="" /><h3>Управление документами</h3></div>
<?php if($files): ?>
<div class="b-news-list b-guest">
    <ul>
        <?php foreach($files as $file): $file->important == 1 ? $class = "b-important" : $class = "";?>
        <li><span class="b-left b-guest-left <?=$class?>"><a href="<?=$hostname?>admin/documents/edit/<?=$file->id?>"><?=$file->name?></a></span><span class="b-right b-guest-right"><?=$file->addtime->format('d.m.Y H:i')?></span></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>
<?=$pagelist?>