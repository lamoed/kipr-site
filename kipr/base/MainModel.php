<?php

// Базовый класс модель, от которого должны наследоваться все новые модели
class MainModel
{
    // Проверка на загрузку ActiveRecord
    private $ormLoaded = 0;
    private $events = array();

    final function __construct() {}

    // Загрузка классов для работы с ActiveRecord
    public function ormLoad() {
        if(!$this->ormLoaded) {
            Core::load('ActiveRecord');
            Cfg::inst()->set_connections(Cfg::inst()->get('connections'));
            if((bool)Cfg::inst()->get('sql_log')) Cfg::inst()->set_logger('Log');
            $this->ormLoaded = 1;
        }
    }

    /**
     * @return ActiveRecord\Model
     */
    public function ormModel($name) {
        if($this->ormLoaded) {
            // Двойная проверка, чтобы лишний раз не подгружать классы
            if(!class_exists($name)) {
                Core::load($name, 'model', DS.'tables');
            }
            if(!class_exists($name)) {
                throw new Exception("Модели {$name} не существует");
            }
            return new $name;
        } else {
            throw new Exception("Модель {$name} не может быть загружена, т.к. отсутствует
            подключение к базе данных");
        }
        
    }

    // $autoset - автоматическое или ручное создание класса библиотеки
    public function libLoad($name, $autoset = 1) {
        if(!class_exists($name)) {
            Core::load($name, 'library');
        }
        if(!class_exists($name)) {
            throw new Exception("Библиотеки {$name} не существует");
        }
        if($autoset == 1) {
            $name = strtolower($name);
            $this->libAdd($name, new $name);
        }
    }

    private function libAdd($name, Library $lib) {
        $this->$name = $lib;
    }

    // Проверка на вызов функции с автоматической загрузкой ActiveRecord моделей
    public function __call($name, $arguments) {
        $prefix = 'db';
        $real_name = substr($name, strlen($prefix));
        if(strpos($name, $prefix) === 0 && method_exists($this, $real_name)) {
            $this->ormLoad();
            $func = new ReflectionMethod($this, $real_name);
            // Добавил return, чтобы было возвращаемое значение у вызываемой функции
            return $func->invokeArgs($this, $arguments);
        } else {
            throw new Exception("Метода {$name} не существует либо он защищен");
        }
    }

    // Добавление события к модели
    public function attachEvent($event) {
        $event = trim($event);
        if(method_exists($this, $event)) {
            $this->events[$event] = $event;
            return true;
        }
        throw new Exception("Метод $event отсутствует в модели ".get_class($this));
        return false;
    }

    // Удаление события модели
    public function detachEvent($event) {
        $event = trim($event);
        unset($this->events[$event]);
        return true;
    }

    // Запуск всех событий модели
    public function runEvents() {
        foreach($this->events as $key => $value) {
            $this->$value();
        }
    }
}