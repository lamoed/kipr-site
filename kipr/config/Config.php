<?php

$config = array();

$config['hostname'] = 'http://kipr/';
// Установка пути до папки проекта
$config['project'] = FWPATH . 'project' . DS;
// Название контроллера для загрузки по умолчанию
$config['default_cnt'] = 'Main';
// Данные для подключения к базе данных
$config['connections'] = array('development' => 'mysql://root:lamoed21@127.0.0.1/kipr');
// Mysql кодировка (cp1251 или utf8)
$config['database_encoding'] = 'cp1251';
// Запись sql запросов
$config['sql_log'] = 0;
// Показывать ошибки или нет
$config['show_errors'] = 1;
// Файл заголовков для страниц, считается от папки kipr и без начального слэша
$config['title_file']  = 'project/tmp/titles/titles.php';
/**
 * @todo Сделать замену / всюду где он лишний
 */
// Пути для роутинга, больший приоритет у записей, которые находятся выше
// Ставить пути без / на конце чтобы правильно работало
$config['routes']      = array(
                            'admin/forum'        => 'admin/main',
                            'admin'              => 'admin/main',
                            'commentadd'         => 'main/commentadd',
                            'contacts'           => 'main/contacts',
                            'mailboxunsubscribe' => 'main/mailboxunsubscribe',
                            'mailboxconfirm'     => 'main/mailboxconfirm',
                            'allnews'            => 'main/allnews',
                            'news'               => 'main/news'
                         );