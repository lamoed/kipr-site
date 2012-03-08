<?php

class Main extends MainController
{
    public $allnews_page = 15;

    function Head() {
        $this->view->setTitle($this->getTitle());
    }

    // Вывод главной страницы всего сайта
    function First() {
        $this->view->news = $this->mdl()->dbnewsGet();
        $this->view->studocs = $this->mdl()->documentsGet('student');
        $this->view->abitdocs = $this->mdl()->documentsGet('abiturient');
        $this->view->render('main/index');
    }

    // Вывод каждой отдельной новости
    function news($id = 1) {
        $safeid = $this->mdl()->idCheck($id);
        $this->view->months = array(
            1  =>  'Января',
            2  =>  'Февраля',
            3  =>  'Марта',
            4  =>  'Апреля',
            5  =>  'Мая',
            6  =>  'Июня',
            7  =>  'Июля',
            8  =>  'Августа',
            9  =>  'Сентября',
            10 =>  'Октября',
            11 =>  'Ноября',
            12 =>  'Декабря'
        );
        $result = $this->mdl()->dbnewsShow($safeid);
        if(!$result) $this->redirect('/');
        if(strlen($result->text) < 10) $result->text = $result->announce;
        $this->view->news_item = $result;
        $this->view->setTitle($this->view->getTitle().' - '.$result->name);
        $this->view->render('main/newspage');
    }

    function allnews($page = 1) {
        $page     = $this->mdl()->idCheck($page);
        $num      = $this->mdl()->dbcountBigger('news', $this->allnews_page);
        if($num) {
            $max_page = ceil($num / $this->allnews_page) - 1;
            $page -= 1;
            if($page < 1 || $page > $max_page) $page = 0;
            $begin = $page * $this->allnews_page;
            $this->view->news  = $this->mdl()->newsRecords($begin, $this->allnews_page);
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/allnews');
            $this->view->render('main/allnews');
        } else {
            $this->view->news = $this->mdl()->newsRecords();
            $this->view->render('main/allnews');
        }
    }

    function mailboxAdd() {
        if(!empty($this->data->post['newsemail'])) {
            $this->mdl()->mailAdd($this->data->post['newsemail'], $this->data->server['REMOTE_ADDR']);
        } else {
            $this->mdl()->ajaxAnswer('Введите название почтового ящика в поле');
        }
    }

    // Подтверждение подписки на почту
    function mailboxConfirm($key = '') {
        if(!empty($key)) {
            if($this->mdl()->dbmailKeyConfirm($key)) {
                $this->view->mail_message = 'Ключ подтверждения принят';
            } else {
                $this->view->mail_message = 'Неправильный ключ подтверждения';
            }
            $this->view->render('main/subscribe');
        } else {
            $this->view->mail_message = 'Неправильный либо пустой ключ подтверждения';
            $this->view->render('main/subscribe');
        }
    }

    // Отмена подписки на почтовую рассылку
    function mailboxUnsubscribe($key = '') {
        if(!empty($key)) {
            if($this->mdl()->dbmailKeyUnsubscribe($key)) {
                $this->view->mail_message = 'Ключ отмены подписки принят, Вы больше не будете уведомляться о новостях';
            } else {
                $this->view->mail_message = 'Неправильный ключ отмены подписки';
            }
            $this->view->render('main/subscribe');
        } else {
            $this->view->mail_message = 'Неправильный либо пустой ключ отмены подписки';
            $this->view->render('main/subscribe');
        }
    }

    public function contacts() {
        $this->showPage('contacts');
    }

    private function showPage($name) {
        $res = $this->mdl()->dbgetPage($name);
        if(empty($res)) $this->redirect('/');
        $this->view->setTitle($res->header);
        $this->view->page = $res;
        $this->view->render('main/page');
    }

    // Добавление комментария к новости
    public function commentAdd() {
        $result = $this->mdl()->dbcommentInsert($this->data->post);
        if($result !== true) {
            $this->view->errors = $result;
            $this->view->error_message = $result['message'];
            $this->view->answer = $this->mdl()->answer;
        }
        $this->forward($this, 'news', '', array($this->data->post['news']));

    }
}