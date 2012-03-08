<?php

// Таблица новостей
class News extends ActiveRecord\Model
{
     static $table_name = 'news';
     
     static $has_many = array(
            array('newscomment', 'order' => 'pubtime asc')
         );
}