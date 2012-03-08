<?php

// ������� ����� ���
class MainView
{
    private $basedir;
    private $layout = 'layout';
    private $default_params = array();

    public function __construct() {
        $this->basedir = Cfg::inst()->get('project') . 'show' . DS;
        $this->default_params['hostname'] = Cfg::inst()->get('hostname');
    }
    
    function fetchPartial($template, $params = array()) {
        if(!is_array($params)) $params = array();
        $params = array_merge($params, $this->default_params);
        extract($params);
        ob_start();
        $path = $this->basedir . $template . '.php';
        if(!file_exists($path)) throw new Exception("������ ���� ({$path}) �� ����������");
        require_once $path;
        return ob_get_clean();
    }

    // ������� �������������� ������ � ����������� $params
    function renderPartial($template, $params = array()) {
        echo $this->fetchPartial($template, $params);
    }

    // �������� �������������� � ���������� $content layout-�
    // ������ � ����������� $params
    function fetch($template, $params = array()) {
        $content = $this->fetchPartial($template, $params);
        return $this->fetchPartial($this->layout, array('content' => $content));
    }

    // ������� �������������� � ���������� $content layout-�
    // ������ � ����������� $params
    function render($template, $params = array()) {
        echo $this->fetch($template, $params);
    }

    public function __set($name, $value) {
        $this->default_params[$name] = $value;
    }

    public function setLayout($name) {
        $this->layout = $name;
    }

    public function setTitle($str) {
        $this->default_params['page_title'] = $str;
    }

    public function getTitle() {
        return $this->default_params['page_title'];
    }
}