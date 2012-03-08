<?php

class UrlProcessor
{
    public  $method;
    public  $folder;
    public  $params = array();
    private $parts = array();
    private $controller;
    
    function __construct(IncomingData $data) {
        $data->request_uri = $this->routeCheck(strtolower($data->request_uri));
        $this->urlParse($data->request_uri);
        $this->confirm();
    }

    // Разбор строки запроса и формирование нужных частей для запуска
    private function urlParse($uri) {
        $uri = $this->urlPrepare($uri);
        foreach(explode('/', $uri) as $part) {
            $this->partCheck($part) ? $this->parts[] = $part :  $this->parts[] = '';
        }
        if(in_array($this->parts[0], Core::$folders)) {
            $this->folder = DS . $this->parts[0];
            Cfg::inst()->set(array('folder' => $this->folder));
            array_shift($this->parts);
        }
        if(!empty($this->parts[0])) {
            $this->controller = $this->parts[0];
            array_shift($this->parts);
            if(!empty($this->parts[0])) {
                $this->method = $this->parts[0];
                array_shift($this->parts);
                if(!empty($this->parts[0]) && !empty($this->parts))
                    $this->params = $this->parts;
                }
            }
        }

    // Подготовка строки запроса к проверке
    private function urlPrepare($url) {
        $slash = '/';
        $indexphp = 'index.php/';
        $url = $this->extraCut($slash, $url);
        $url = $this->extraCut($indexphp, $url);
        return $url;
    }


    // Проверка строки запроса на отсутствие запрещенных символов и SQL инъекций
    private function partCheck($str) {
        $str = trim($str);
        $str = str_replace(array('\\', '\\x00', '\\00'), '', $str);
        if(preg_match('|^([-a-zA-Z0-9_#%]+)$|i', $str)) return true;
        return false;
    }

    /**
     * @todo сделать проверку на наличие валидных символов даже при наличии путей маршрутизации в полученном адресе!
     */
    // Обработка роутинга из файла конфигурации
     private function routeCheck($uri) {
        foreach (Cfg::inst()->get('routes') as $route => $value) {
            if(strpos($uri, $route)) {
                $uri = str_replace($route, $value, $uri);
                break;
            }
        }
        return $uri;
    }

    // Вырезание лишних частей ссылки для корректной обработки
    private function extraCut($extra, $str) {
        if(strpos($str, $extra) === 0) {
            $str = substr($str, strlen($extra));
        }
        return $str;
    }

    // Проверка загруженных данных в контроллер и метод на существование
    public function confirm() {
        Core::load($this->controller, 'controller', $this->folder);
        if(!class_exists($this->controller)) {
            $this->controller = Cfg::inst()->get('default_cnt');
            Core::load($this->controller, 'controller', $this->folder);
        }

        $rc = new ReflectionClass($this->controller);
        if(!$rc->hasMethod($this->method)) {
            $this->method = 'First';
        }
    }

    // Запуск нужного контроллера с параметрами
    public function execute() {
        $controller = new $this->controller;
        if(!is_subclass_of($controller, 'MainController')) {
            throw new Exception('Класс контроллер (' . $this->controller . ') должен наследовать MainController');
        }
        // Предварительный метод перед загрузкой нужного
        $head = new ReflectionMethod($this->controller, 'Head');
        $head->invokeArgs($controller, $this->params);
        if($controller->getStop() == 0) {
            $go = new ReflectionMethod($this->controller, $this->method);
            $go->invokeArgs($controller, $this->params);
        }
    }
}