<?php

class Forumauthor extends ActiveRecord\Model
{
    static $table_name = 'forum_authors';
    static $primary_key = 'id_author';

    static $has_one = array(
        // ”казан ключ по которому выбираютс€ дочерние дл€ категории темы
        array('Forumlasttime', 'foreign_key' => 'id_author')
    );
}