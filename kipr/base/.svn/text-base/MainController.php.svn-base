<?php

/**
 * @property MainView            $view
 * @property MainModel           $mdl
 * @property IncomingData        $data
 *
 */

// Базовый контроллер, от которого должны наследоваться все новые контроллеры
abstract class MainController
{
    public $view;
    public $data;
    public $mdl;
    private $models = array();

    // При форвардинге, если он был запущен из предварительного метода отменяется
    // последующий запуск вызванной функции
    private $stop = 0;

    final public function __construct() {
        $this->data = $this->getData();
        $this->mdl = $this->appendModel($this);
        $this->view = $this->appendView();
    }
    
    private function getData() {
        $obj = Core::$inc;
        return $obj;
    }

    // Автоматическая подгрузка модели в контроллер
    private function appendModel(MainController $obj) {
        $name = get_class($obj) . 'Mod';
        Core::load($name, 'model', Cfg::inst()->get('folder'));
        return $this->loadModel($name);
    }

    private function appendView() {
        Core::load('MainView');
        return new MainView();
    }

    private function setStop($value) {
        $this->stop = (bool) $value;
    }

    // Подгрузка модели
    private function loadModel($name) {
        if(class_exists($name)) {
            $mod = new $name;
            if(!is_subclass_of($mod, 'MainModel')) {
                throw new Exception('Класс модель (' . $name . ') должен наследовать MainModel');
            }
            return $mod;
        }
        return NULL;
    }

    private function notify($modelname, $model) {
        if(!empty($this->events[$modelname]) && is_object($model)) {
            foreach($this->events[$model] as $key => $value) {
                
            }
        }
    }

    public function redirect($to = '/') {
        header('Location: '.$to);
    }

    // Пока что делается без проверки на существование
    public function forward($controller, $action, $folder = '', array $params = array()) {
        if(is_object($controller)) {
            $obj = $controller;
            $controller = get_class($controller);
        } else {
            if(!class_exists($controller)) {
                Core::load($controller, 'controller', $folder);
            }
            $obj = new $controller;
        }
        $this->setStop(1);
        $go = new ReflectionMethod($controller, $action);
        $go->invokeArgs($obj, $params);
    }

    public function getStop() {
        return $this->stop;
    }

    public function getCalledMethod() {
        return Cfg::inst()->get('method');
    }

    private function getFolder() {
        return Cfg::inst()->get('folder');
    }

    // Упорядочивание параметров переданных в функцию контроллера
    public function prepareParameters($offset = 0) {
        $stack = debug_backtrace();
        $params = $stack[1]['args'];
        $count = count($params);
        $result = array();
        $start = 1 + $offset;
        for($i = $start; $i < $count; $i+=2) {
            if(empty($params[$i]) && $params[$i] != 0) break;
            $result[$params[$i-1]] = $params[$i];
        }
        return $result;
    }

    public function mdl($modelname = "", array $loadpath = array()) {
        if(empty($modelname)) {
            $model = $this->mdl;
        } else {
            $modelname .= 'Mod';
            if(!array_key_exists($modelname, $this->models)) {
                Core::load($modelname, 'model', $loadpath['folder']);
                $model = $this->loadModel($modelname);
                if($model) {
                    $this->models[$modelname]['obj'] = $model;
                }
            } else {
                $model = $this->models[$modelname]['obj'];
            }
        }

        if(is_object($model)) {
            $model->runEvents();
            return $model;
        }
        return false;
    }

    public function attachModelEvent($event, $model = "") {
        if(empty($model)) {
            return $this->mdl->attachEvent($event);
        }
        if(!array_key_exists(get_class($model), $this->models)) return false;
        return $model->attachEvent($event);
    }

    public function detachModelEvent($event, $model = "") {
        if(empty($model)) $this->mdl->detachEvent($event);
        if(!in_array(get_class($model), $this->models)) return false;
        return $model->detachEvent($event);
    }

    // Заголовок для страницы
    protected function getTitle() {
        $titles_file = Cfg::inst()->get('title_file');
        $sep = '_';
        $title_str = strtolower(get_called_class() . $sep . $this->getCalledMethod());
        $folder = $this->getFolder();

        // Если контроллер находится не в папке по умолчанию
        if($folder) {
            $title_str = $folder . $sep . $title_str;
            $title_str = str_replace(DS, '', $title_str);
        }

        $titles = array();
        if(!empty($titles_file)) {
            if(!file_exists(FWPATH . $titles_file)) {
                throw new Exception('Файл с заголовками не был найден');
            }
            // Чтобы получать массив при многократном вызове функции
            require FWPATH . $titles_file;
        }

        if(array_key_exists($title_str, $titles)) {
            return $titles[$title_str];
        } else {
            return false;
        }
    }

    // Метод который будет загружаться по умолчанию, если не найдется запрашиваемый
    abstract function First();
    // Предварительный метод для всех методов класса
    abstract function Head();
}