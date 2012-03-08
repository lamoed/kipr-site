<?php

class Guestbook extends MainController
{
    
    public function Head() {
        $this->view->setTitle($this->getTitle());
    }

    // Вывод первой старницы гостевой книги
    public function First() {
        $settings = $this->mdl()->dbsettingsGet();
        $num = $this->mdl()->dbcountBigger('guestrecords', $settings->maxguest);
        if($num) {
			if($settings->maxguest < 1) $settings->maxguest = 1;
            $max_page = ceil($num / $settings->maxguest) - 1;
            $page = 0;
            $begin = $page * $settings->maxguest;
            $this->view->records  = $this->mdl()->guestRecords($begin, $settings->maxguest);
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/guestbook/page');
            $this->view->render('guestbook/index');
        } else {
            $this->view->records = $this->mdl()->guestRecords();
            $this->view->render('guestbook/index');
        }
    }

    // Функция постраничного показа записей в гостевой книге
    public function Page($page = 1) {
        $settings = $this->mdl()->dbsettingsGet();
        $page     = $this->mdl()->pageCheck($page);
        $num      = $this->mdl()->dbcountBigger('guestrecords', $settings->maxguest);
        if($num) {
			if($settings->maxguest < 1) $settings->maxguest = 1;
            $max_page = ceil($num / $settings->maxguest) - 1;
            $page -= 1;
            if($page < 1 || $page > $max_page) $page = 0;
            $begin = $page * $settings->maxguest;
            $this->view->records  = $this->mdl()->guestRecords($begin, $settings->maxguest);
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/guestbook/page');
            $this->view->render('guestbook/index');
        } else {
            $this->view->records = $this->mdl()->guestRecords();
            $this->view->render('guestbook/index');
        }
    }

    // Добавление записи в гостевую книгу с проверкой по капче и валидацией входящих данных
    public function Add() {
        $result = $this->mdl()->dbrecordInsert($this->data->post);
        if($result !== true) {
            $this->view->errors = $result;
            $this->view->error_message = $result['message_text'];
            $this->view->answer = $this->mdl()->answer;
        }
        $this->forward($this, 'First');
    }
    
    // Вывод капчи со случайно сгенерированным 4х значным числом и установкой сессии
    function Captcha($number = '') {
        $this->mdl()->captcha(ROOT . '/look/pic/security.png', ROOT . '/look/pic/security.ttf');
    }
}