<?php

class GuestbookMod extends MainModel
{
    public $answer = '';

    // Проверка номера страницы, если он вводится вручную в адресной строке
    public function pageCheck($param) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type' => 'int',
                'name' => 'id',
                'value' => $param,
                'options' => array(
                    'options' => array(
                        'default' => 1,
                    )
                )
            )
        );
        $this->validator->analyze($check);
        return $this->validator->id;
    }

    // Вставка новой записи в гостевую книгу с проверкой входных данных
    public function recordInsert(array $data) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $data['name'],
                'field' => 'Имя',
                'req'   => 1
            ),
            array(
                'type' => 'string',
                'name' => 'message',
                'value' => $data['message'],
                'field' => 'Текст сообщения',
                'req'   => 1
            ),
        );
        if(!empty($data['email'])) {
        $check[] =  array(
                'type' => 'email',
                'name' => 'email',
                'value' => $data['email'],
                'field' => 'Почтовый ящик'
            );
        } else {
            $this->validator->email = "";
        }
        if($this->validator->analyze($check)) {
            if(!$this->answerCheck($data['captcha'])) {
                 // Возвращение сообщений, если в полученных данных была ошибка
                $this->validator->errors['message_text'] = 'Неправильно введен проверочный текст';
                $this->answer = $this->validator->filtered;
                return $this->validator->errors;
            }
            $this->validator->message = nl2br($this->validator->message, true);
            $guestbook = new Guestrecords(array('name' => substr($this->validator->name,0, 32), 'email' => $this->validator->email, 'message' => $this->validator->message));
            $guestbook->save();
            return true;
        } else {
            // Возвращение сообщений, если в полученных данных была ошибка
           $this->answer = $this->validator->filtered;
           return $this->validator->errors;
        }
    }

    // Функция генерации каптчи
    public function captcha($path, $fontpath) {
        $this->libLoad('captcha');
        return $this->captcha->create($path, $fontpath);
    }

    // Проверка введенных данных со значением капчи
    public function answerCheck($value) {
        $this->libLoad('captcha');
        return $this->captcha->sessionCheck($value);
    }

    // Получение количества сообщений в таблице
    public function countBigger($table, $value = 10) {
        $count = $table::count();
        return $count > $value ? $count : false;
    }

    // Получение настроек для сайта
    public function settingsGet() {
        return Settings::first();
    }

    // Вывод меню страниц
    public function pages($max_page, $page, $link) {
        $this->libLoad('paginator');
        return $this->paginator->build($max_page, $page, $link);
    }

    // Получение записей из гостевой книги
    public function guestRecords($offset = 0, $num = 0) {
        if($num) {
            $guest_records = Guestrecords::find('all', array('order' => 'writetime desc', 'offset' => $offset, 'limit' => $num));
        } else {
            $guest_records = Guestrecords::find('all', array('order' => 'writetime desc'));
        }
        return $guest_records;
    }
}
