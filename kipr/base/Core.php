<?php

// Базовый класс запускающий все процессы
class Core
{
    public static $base_classes;
    public static $folders = array();
    public static $inc;
    public  $action;
    
    function __construct($arr_pref) {

        $this->confLoad($arr_pref);
        $this->projectScan(Cfg::inst()->get('project') . 'manage');
        self::$inc = new IncomingData();
        $this->action = new UrlProcessor(self::$inc);
        $this->configWrite(array('method' => $this->action->method, 'folder' => $this->action->folder));
    }

    // Автозагрузчик практически всех новых создаваемых файлов
    public static function load($class, $type = NULL, $folder = '') {
        $class = ucfirst($class);
        if(in_array($class, self::$base_classes)) {
            require_once FWPATH . 'base' . DS . $class . '.php';
        }
        if(!empty($type)) {
            $type = strtolower($type);

            switch($type) {
                case 'controller':
                    $path = Cfg::inst()->get('project') . 'manage' . $folder . DS . $class . '.php';
                    if(file_exists($path)) {
                        require_once $path;
                    }
                    break;
                case 'model':
                    $path = Cfg::inst()->get('project') . 'modify' . $folder . DS . $class . '.php';
                    if(file_exists($path)) {
                        require_once $path;
                    }
                    break;
                case 'library':
                    $path = FWPATH . 'libs' . DS . $class . '.php';
                    if(file_exists($path)) {
                        require_once $path;
                    }
                    break;
                default:
                    return NULL;
            }
            // Загрузка классов таблиц
        } else {
            $path = Cfg::inst()->get('project') . 'modify' . DS . 'tables' . DS . $class . '.php';
            if(file_exists($path)) {
                require_once $path;
            }
        }
    }

    // Загрузка файла конфигурации
    private function confLoad($arr_pref) {
        $this->baseInit();
        $this->configWrite($arr_pref);
    }


    // Инициализация базовых классов для загрузки по мере необходимости
    private function baseInit() {
        self::$base_classes = array(
            'Cfg',
            'Log',
            'Library',
            'MainView',
            'MainModel',
            'IncomingData',
            'ActiveRecord',
            'UrlProcessor',
            'MainController'
        );
    }

    public function configWrite(array $value) {
        Cfg::inst()->set($value);
    }


    // Сканирование папок контроллеров на наличие дополнительных уровней вложенности
    private function projectScan($path) {
        $dh = opendir($path);
        while(false !== ($filename = readdir($dh))) {
            if(is_dir($path . DS . $filename)) {
                if($filename == '.' || $filename == '..') continue;
                self::$folders[] = strtolower($filename);
            }
        }
    }


    // Запуск контроллера
    public function run() {
        $this->action->execute();
    }
}