<div class="b-news-header b-docs-head"><h3><?=$docs_name?></h3></div>
<?php if($docs):?>
<div class="b-files">
    <?php foreach($docs as $doc):?>
    <div class="b-doc-cont">
        <div class="b-left"> <?php $image = $doc['ext'] ?  "<img src=\"/look/pic/{$doc['ext']}.png\" alt=\"\" />" : ""; ?><?=$image?><?=$doc['name']?>
            <div class="b-sep"></div>
            <?=$doc['comment']?>
        </div>
        <div class="b-right">
            <div><?=$doc['added']?></div>
            <div>Размер: <?=$doc['size']?></div>
            <div><a href="<?=$doc['link']?>">СКАЧАТЬ</a></div>
        </div>
        <div class="b-sep"></div>
    </div>
    <? endforeach;?>
</div>
<?php //var_dump($sections, $prev);?>

<div class="b-sections">
    <?php if($sections):?>
    <div class="b-part">
        <b>Разделы</b>
        <ul>
            <?php foreach($sections as $section):?>
            <li><a href="<?=$hostname?><?=$prevmain?>/documents/<?=$section->filename?>"><?=$section->name?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
    <?php if(!$first):?>
    <div class="b-part">
        <b>Обратно</b>
        <ul>
            <?php $main = ($prevmain == 'student') ? "<a href=\"{$hostname}student/documents\">Студенту</a>" : "<a href=\"{$hostname}abiturient/documents\">Абитуриенту</a>";?>
            <li><?=$main?></li>
            <?php foreach($prev as $prevsec):?>
            <li><a href="<?=$hostname?><?=$prevmain?>/documents/<?=$prevsec->filename?>"><?=$prevsec->name?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
</div>
<?php endif;?>