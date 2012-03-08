<?php


/*
 * Мой пример:
 * $this->libLoad('validator');
 * $values = array(
 *      array('value' => $pass,
 *            'type' => 'int',
 *            'name' => 'password',
 *         'options' => array(
 *              'flags' => FILTER_FLAG_ALLOW_HEX,
 *              'options' => array(
 *                  'min_range' => 5
 *               )
 *          )
 *       ),
 *      array('value' => $name,
 *            'type'  => 'string',
 *            'name'  => 'username'
 *      )
 *  );
 *  $this->validator->analyze($values);
 * 
 * Оригинальный пример:
 * $options = array(
 *  'options' => array(
 *      'default' => 3, // value to return if the filter fails
 *      // other options here
 *      'min_range' => 0
 *     ),
 *  'flags' => FILTER_FLAG_ALLOW_OCTAL,
 * );
 * $var = filter_var('0755', FILTER_VALIDATE_INT, $options);
 *
 */
         
class Validator extends Library
{
    public $errors = array();
    public $filtered = array();

//    FILTER_FLAG_NO_ENCODE_QUOTES,
//    FILTER_FLAG_STRIP_LOW,
//    FILTER_FLAG_STRIP_HIGH,
//    FILTER_FLAG_ENCODE_LOW,
//    FILTER_FLAG_ENCODE_HIGH,
//    FILTER_FLAG_ENCODE_AMP
    private function validate_string($param, $options = array()) {
        return $this->validate($param, FILTER_SANITIZE_STRING, $options);
    }

//    min_range , max_range
//    FILTER_FLAG_ALLOW_OCTAL,
//    FILTER_FLAG_ALLOW_HEX
    private function validate_int($param, $options = array()) {
        return $this->validate($param, FILTER_VALIDATE_INT, $options);
    }
    
    private function validate_email($param, $options = array()) {
        return $this->validate($param, FILTER_VALIDATE_EMAIL, $options);
    }

//    FILTER_NULL_ON_FAILURE 
    private function validate_bool($param, $options = array()) {
        return $this->validate($param, FILTER_VALIDATE_BOOLEAN, $options);
    }

//    regexp 
    private function validate_regexp($param, $options = array()) {
        return $this->validate($param, FILTER_VALIDATE_REGEXP, $options);
    }

//    FILTER_FLAG_STRIP_LOW,
//    FILTER_FLAG_STRIP_HIGH,
//    FILTER_FLAG_ENCODE_HIGH
    private function validate_specialchars($param, $options = array()) {
        return $this->validate($param, FILTER_SANITIZE_SPECIAL_CHARS, $options);
    }

//    FILTER_FLAG_IPV4,
//    FILTER_FLAG_IPV6,
//    FILTER_FLAG_NO_PRIV_RANGE,
//    FILTER_FLAG_NO_RES_RANGE
    private function validate_ip($param, $options = array()) {
        return $this->validate($param, FILTER_VALIDATE_IP, $options);
    }

//    FILTER_FLAG_SCHEME_REQUIRED - Requires URL to be an RFC compliant URL (like http://example)
//    FILTER_FLAG_HOST_REQUIRED - Requires URL to include host name (like http://www.example.com)
//    FILTER_FLAG_PATH_REQUIRED - Requires URL to have a path after the domain name (like www.example.com/example1/test2/)
//    FILTER_FLAG_QUERY_REQUIRED - Requires URL to have a query string (like "example.php?name=Peter&age=37")

    private function validate_url($param, $options = array()) {
        return $this->validate($param, FILTER_VALIDATE_URL, $options);
    }

    private function validate($value, $filter, $options = array()) {
        return filter_var($value, $filter, $options);
    }
    
    public function analyze(array $params) {
        foreach($params as $param) {

            $name = $param['name'];

            // Пропуск пустых необязательных параметров
            /**
             * @todo Исправить серьезный недочет, если переданное значение должно быть равно 0, то оно может просто игнорироваться и не дойти до проверки
             */
            if(empty($param['value']) && $param['req'] != 1) {
                continue;
            } elseif(empty($param['value']) && $param['req'] == 1) {
                if(!empty($param['field'])) {
                    $this->errors[$name] = 'Не заполнено поле "' . $param['field'] . '"';
                } else {
                    $this->errors[$name] = 'Не заполнено поле ' . $param['name'];
                }
                continue;
            }

            if(method_exists($this, 'validate_'.$param['type'])){
                $func = 'validate_'.$param['type'];

                $result = empty($param['options']) ? $result = $this->$func($param['value']) :
                      $result = $this->$func($param['value'], $param['options']);
                /**
                 * @todo Исправить серьезный недочет, если устранить предыдущую ошибку и будет, например, выводиться результат булева фильтра, то это может вызвать ошибку
                 */
                if(!$result) {
                    if(!empty($param['field'])) {
                        $this->errors[$name] = 'Ошибка введенных данных в поле "'.$param['field'].'"';
                    } else {
                        $this->errors[$name] = 'Ошибка введенных данных в поле '.$param['name'];
                    }
                }
                $this->$param['name'] = $result;
                $this->filtered[$param['name']] = $result;
            } else {
                throw new Exception("Данный тип проверки {$param['type']} отсутствует");
            }
        }
        if(!empty($this->errors)) return false;
        return true;
    }
}