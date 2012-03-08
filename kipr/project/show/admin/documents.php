<div class="b-news-header"><img src="/look/pic/folder_full.png" alt="" /><h3>Управление документами</h3></div>
<div class="b-news-list">
    <?php if($message_str):?><div class="b-error b-admin-error"><?=$message_str?></div><?php endif;?>
    <ul>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/documents/scan/">Сканировать документы</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/documents/files/">Список всех документов</a></span></li>
        <li><span class="b-left"><img src="/look/pic/accept.png" alt="" /><a href="<?=$hostname?>admin/documents/folders/">Список всех папок</a></span></li>
    </ul>
</div>