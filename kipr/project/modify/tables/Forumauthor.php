<?php

class Forumauthor extends ActiveRecord\Model
{
    static $table_name = 'forum_authors';
    static $primary_key = 'id_author';

    static $has_one = array(
        // ������ ���� �� �������� ���������� �������� ��� ��������� ����
        array('Forumlasttime', 'foreign_key' => 'id_author')
    );
}