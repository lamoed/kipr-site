<?php

class Forumcategory extends ActiveRecord\Model
{
    static $table_name = 'forum_forums';
    static $primary_key = 'id_forum';

    static $has_many = array(
        // ”казан ключ по которому выбираютс€ дочерние дл€ категории темы
        array('Forumtheme', 'foreign_key' => 'id_forum', 'order' => 'time desc')
    );
}