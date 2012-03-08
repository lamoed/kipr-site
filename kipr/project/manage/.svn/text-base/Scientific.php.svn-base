<?php

class Scientific extends MainController
{
    public function Head() {
         $this->view->setTitle($this->getTitle());
    }

    public function First() {
        $this->showPage('awards');
    }

    public function Publications() {
        $this->showPage('publications');
    }

    public function Patents() {
        $this->showPage('patents');
    }

    public function Exhibition() {
        $this->showPage('exhibition');
    }

    public function Awards() {
        $this->showPage('awards');
    }

    private function showPage($name) {
        $res = $this->mdl('Main')->dbgetPage($name);
        if(empty($res)) $this->forward($this, 'first');
        $this->view->setTitle($res->header);
        $this->view->page = $res;
        $this->view->render('main/page');
    }
}