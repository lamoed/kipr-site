<?php

class Forumtheme extends ActiveRecord\Model
{
    static $primary_key = 'id_theme';
    static $table_name = 'forum_themes';

    static $has_many = array(
        // ”казан ключ по которому выбираютс€ дочерние дл€ категории темы
        array('Forumpost', 'foreign_key' => 'id_theme', 'order' => 'time desc', 'conditions' => 'hide != "hide"')
    );
    static $belongs_to = array(
        array('Forumcategory', 'foreign_key' => 'id_forum', 'conditions' => 'hide != "hide"'),
        array('Forumauthor', 'foreign_key' => 'id_author'),
        array('Lastauthor', 'foreign_key' => 'id_last_author', 'class_name' => 'Forumauthor')
    );
}