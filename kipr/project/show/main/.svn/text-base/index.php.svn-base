<?php if($news):?>
<div class="b-news b-main">
    <div class="b-news-left">
        <div class="b-news-header"><img src="/look/pic/news.png" alt="" /><h3>Новости</h3></div>
            <?php foreach($news as $news_item): ?>
        <div class="b-news-new">
            <h2><a href="<?=$hostname?>news/<?=$news_item->id?>"><?=$news_item->name?></a></h2>
            <div class="b-announce">
                        <?=$news_item->announce?>
            </div>
            <div class="b-news-separator"></div>
            <div class="b-date">Опубликовано <b><?=$news_item->pubtime->format('d.m.y')?></b></div>
            <div class="b-comment"><img src="/look/pic/comment.png" alt="" />Комментариев <b><?=$news_item->text?></b></div>
        </div>
            <?php endforeach;?>
        <div class="b-allnews"><a href="<?=$hostname?>allnews/">&larr; предыдущие новости</a></div>
    </div>
    <div class="b-news-right">
        <img src="/look/pic/3b.jpg" alt="" class="b-center" />
        <div class="b-main-text">
            <div class="b-staple-top"></div>
            <p>Наша кафедра традиционно обеспечивает высокий  уровень фундаментальной и прикладной подготовки студентов, дает универсальные знания и навыки творческого подхода к решению технических задач, заботится об их трудоустройстве на интересную инженерную работу с достойной оплатой в области проектирования и сервиса радиоэлектронных средств. Студенты кафедры – частые победители в конкурсах на гранты губернатора Челябинской области и в других конкурсах для творчески одарённой молодёжи, победители в спортивных мероприятиях.</p>
            <div class="b-staple-bottom"></div>
        </div>
        <img src="/look/pic/mainpic1.jpg" alt="Студенты кафедры" />
        <img src="/look/pic/mainpic2.jpg" alt="Студенты кафедры" />
    </div>
</div>
<div class="b-sep"></div>
<?php endif;?>
<?php if($studocs || $abitdocs):?>
<div class="b-documents">
    <div class="b-documents-header">
        <img src="/look/pic/documents.png" alt="" /><h3>Документы</h3>
        <div class="b-documents-center">
            <?php if($studocs):?>
            <div class="b-case">
                <div class="b-header">Студенту</div>
                <div class="b-content">
                    <ul class="b-list">
                        <?php foreach($studocs as $doc): $image = $doc['ext'] ?  "<img src=\"/look/pic/{$doc['ext']}.png\" alt=\"\" />" : ""; ?>
                            <li class="b-item"><?=$image?><a href="<?=$doc['link']?>"><?=$doc['name']?> <?=$doc['size']?></a></li>
                        <? endforeach;?>
                    </ul>
                </div>
            </div>
            <?php endif;?>
            <?php if($abitdocs):?>
            <div class="b-case">
                <div class="b-header">Абитуриенту</div>
                <div class="b-content">
                    <ul class="b-list">
                        <?php foreach($abitdocs as $doc): $image = $doc['ext'] ?  "<img src=\"/look/pic/{$doc['ext']}.png\" alt=\"\" />" : ""; ?>
                            <li class="b-item"><?=$image?><a href="<?=$doc['link']?>"><?=$doc['name']?> <?=$doc['size']?></a></li>
                        <? endforeach;?>
                    </ul>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<?php endif;?>