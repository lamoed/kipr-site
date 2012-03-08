<?php

class Common extends Library
{
    
    function __construct() {

    }
    
    public function global_info() {
        $str = '<pre>';
        $str .= '$_REQUEST = ' . print_r($_REQUEST, 1) .
                 '$_SERVER = ' . print_r($_SERVER, 1) .
                 '$_GET = '    . print_r($_GET, 1) .
                 '$_POST = '   . print_r($_POST, 1) .
                 '$_COOKIE = ' . print_r($_COOKIE, 1) .
                 '$_ENV = '    . print_r($_ENV, 1) .
                 '$_SESSION = '. print_r($_SESSION, 1);
        $str .= '</pre>';
        return $str;
    }
}