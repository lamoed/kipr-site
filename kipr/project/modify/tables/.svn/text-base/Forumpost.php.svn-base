<?php

class Forumpost extends ActiveRecord\Model
{
    static $table_name = 'forum_posts';
    static $primary_key = 'id_post';

    static $belongs_to = array(
        array('forumauthor', 'foreign_key' => 'id_author'),
        array('forumtheme', 'foreign_key' => 'id_theme')
    );
}