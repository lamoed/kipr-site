<?php

//  ласс обертка дл€ глобальных массивов
class IncomingData
{
    public $post;
    public $get;
    public $server;
    public $request;
    public $cookie;
    public $request_uri;
    
    function __construct() {
        $this->set_get($_GET);
        $this->set_post($_POST);
        $this->set_server($_SERVER);
        $this->set_cookie($_COOKIE);
        $this->set_request($_REQUEST);
        
        unset($_GET, $_POST, $_SERVER, $_REQUEST, $_ENV);
//        ≈сли удал€ть куки то каждый раз заного пересоздаетс€ сесси€
//        unset($_GET, $_POST, $_SERVER, $_REQUEST, $_ENV, $_COOKIE);
    }
    
    private function set_get($glob) {
        $this->get = $glob;
    }
    
    private function set_post($glob) {
        $this->post = $glob;
    }
    
    private function set_server($glob) {
        $this->server = $glob;
        $this->request_uri = $glob['REQUEST_URI'];
    }
    
    private function set_request($glob) {
        $this->request = $glob;
    }

    private function set_cookie($glob) {
        $this->cookie = $glob;
    }
}