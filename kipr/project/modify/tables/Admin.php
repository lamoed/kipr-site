<?php

// Таблица администраторов для входа в админку
class Admin extends ActiveRecord\Model
{
    // Допустимые символы для ключа авторизации админа
    static $validates_format_of = array(
        array('authkey', 'with' => '/^[a-zA-Z0-9_-]*$/', 'allow_blank' => false)
    );

}