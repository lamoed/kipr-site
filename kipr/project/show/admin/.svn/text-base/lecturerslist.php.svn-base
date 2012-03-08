<div class="b-news-header"><img src="/look/pic/lecturer.png" alt="" /><h3>Страницы преподавателей</h3><span class="b-margin"><img src="/look/pic/lecturer_add.png" alt="" /><h3><a href="<?=$hostname?>admin/lecturers/add/">Добавить преподавателя</a></h3></span></div>
<?php if($lecturers): ?>
<div class="b-news-list">
    <ul>
        <?php foreach($lecturers as $lecturer): ?>
        <li><span class="b-left"><img src="/look/pic/delete.png" alt="" onmouseout="document.body.style.cursor='default'" onmouseover="document.body.style.cursor='pointer'" onclick="sure('<?=$lecturer->linkname?>', 'lecturers');" /><a href="<?=$hostname?>admin/lecturers/edit/<?=$lecturer->linkname?>"><?=$lecturer->header?></a></span></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>