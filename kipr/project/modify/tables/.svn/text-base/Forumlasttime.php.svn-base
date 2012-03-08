<?php

class Forumlasttime extends ActiveRecord\Model
{
    static $table_name = 'forum_last_time';
    static $primary_key = 'id_author';

    static $has_one = array(
        array('Forumauthor', 'foreign_key' => 'id_author')
    );
}