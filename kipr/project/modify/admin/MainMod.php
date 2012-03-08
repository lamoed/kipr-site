<?php

class MainMod extends MainModel
{
    public  $files;
    public  $answer = '';
    private $salt = '7rAbuh8xub';
    private $frombox     = 'admin@kipr.susu.ac.ru';
    private $basepath;
    private $forum_salt = '5kDG1luwPjf'; // Соль из модели форума

// Проверка файлов "куков" для сравнения со значением в базе данных
    public function cookieCheck($unique, $time) {
        $admin = Admin::find_by_authkey($unique);
        if(empty($admin)) {
            return false;
        } else {
            $timeleft = $admin->timeleft->getTimestamp();
            if(($time - $timeleft) > 0) {
                $this->setAnswer('Превышено время сессии администратора. Войдите повторно');
                return false;
            }
            if($admin->authkey !== $unique) {
                $this->setAnswer('Данные сессии не совпадают');
                return false;
            }
            return $this->cookieUpdate($admin);
        }
    }

    public function libsLoad() {
        $this->libLoad('validator');
    }

// Валидация данных из куков и сравнения с базой данных
    public function userCheck($name, $pass) {
        $values = array(
            array('value' => $name,
                'type'    => 'string',
                'name'    => 'username'
            ),
            array('value' => $pass,
                'type'    => 'string',
                'name'    => 'pass',
            )
        );
        if(!$this->validator->analyze($values)) {
            $this->setAnswer('Неправильно введены данные');
            return false;
        }
        $result = Admin::find_by_name($name);
        if($result) {
            $compare = $this->passCompare($pass, $result->password);
            if($compare) {
                return $this->cookieUpdate($result, 1);
            } else {
                $this->setAnswer('Ошибка ввода пароля');
                return false;
            }
        } else {
            $this->setAnswer('Данного пользователя не существует');
            return false;
        }
    }

// Подключение скрипта Ckeditor для создания динамичесго и удобного поля ввода новостей
    public function connectCk($fieldname) {
        $script = '<script type="text/javascript" src="/look/js/ckeditor/ckeditor.js"></script>
                   <script type="text/javascript">
                    window.onload = function()
                    {
                            CKEDITOR.replace( "' . $fieldname . '" );
                    };
                   </script>';
        return $script;
    }

// Проверка количества сообщений в таблице
    public function countBigger($table, $value = 10) {
        $count = $table::count();
        return $count > $value ? $count : false;
    }

 // Проверка количества сообщений в таблице с дополнительными условиями
    public function countBiggerCondition($table, array $conditions, $value = 10) {
        $count = $table::count($conditions);
        return $count > $value ? $count : false;
    }

// Вывод списка новостей
    public function newsList($offset = 0, $num = 0) {
        if($num) {
            $news = News::find('all', array('order' => 'pubtime desc', 'offset' => $offset, 'limit' => $num));
        } else {
            $news = News::find('all', array('order' => 'pubtime desc'));
        }
        return $news;
    }

// Вставка новости в базу данных
    public function newsInsert($name, $announce, $newstext) {
        $this->libLoad('htmlpurifier');
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $name,
                'field' => 'Заголовок',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'announce',
                'value' => $announce,
                'field' => 'Анонс',
                'req'   => 1
            )
        );
        
        // Проверка вставляемого текста на XSS
        $settings = HTMLPurifier_Config::createDefault();
        // Жесткие костыли
        // Может лечиться $settings->set('Core', 'EscapeNonASCIICharacters', true);
        // но тогда при загрузке библиотеки надо передавать параметры в конструктор
        // что ещё не реализовано
        // Все это делается для того чтобы пурифаер нормально проверял текст и без изменений
        // вставлял его в базу данных
        $newstext = $this->toutf8($newstext);
        $newstext = $this->htmlpurifier->purify($newstext , $settings);
        $newstext = $this->to1251($newstext);

        if($this->validator->analyze($params)) {
            if(empty($newstext) || strlen($newstext) < 7) {
                $this->validator->errors['message'] = 'Не заполнен текст новости';
                $this->setAnswer($this->validator->filtered);
                return $this->validator->errors;
            }
        } else {
            // Возвращение сообщений, если в полученных данных была ошибка
            $this->validator->filtered['newstext'] = $newstext;
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
        
        $news_item = new News(array('name' => $this->validator->name,
                      'announce' => $this->validator->announce, 'text' => $newstext));
        $news_item->save();
        // Рассылка почты подписчикам
        $this->newsSubscribers($news_item->id, $this->validator->name, $this->validator->announce);
        return true;
    }

// Удаление новости со всеми комментариями
    public function newsDelete($id) {
        $news_item = News::find($id);
        $count = count($news_item->newscomment);
        if($count > 0) {
            foreach ($news_item->newscomment as $comment) {
                $comment->delete();
            }
        }
        return $news_item->delete();
    }

    // Вставка страницы преподавателя в базу данных
    public function lecturerInsert($name, $text, $rang, $linkname = '') {
        $this->libLoad('htmlpurifier');
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $name,
                'field' => 'Ф.И.О.',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'rang',
                'value' => $rang
            ),
            array(
                'type'  => 'string',
                'name'  => 'linkname',
                'value' => $linkname
            )
        );

        // Проверка вставляемого текста на XSS
        $settings = HTMLPurifier_Config::createDefault();
        $text = $this->toutf8($text);
        $text = $this->htmlpurifier->purify($text , $settings);
        $text = $this->to1251($text);
        
        if($this->validator->analyze($params)) {
            if(empty($text) || strlen($text) < 7) {
                $this->validator->filtered['lectext'] = $text;
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Не заполнен текст';
                return $this->validator->errors;
            }

            if(empty($this->validator->linkname)) {
                $linkname = $this->fileRename($this->validator->name);
            } else {
                $linkname = $this->fileRename($this->validator->linkname);
            }
            $lecturer_page = new Page(array('header' => $this->validator->name, 'type' => 'prepodavateli',
                          'linkname' => $linkname, 'text' => $text, 'additional' => $rang));
            $lecturer_page->save();
            return true;
        } else {
            // Возвращение сообщений, если в полученных данных была ошибка
            $this->validator->filtered['lectext'] = $text;
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

     public function lecturerUpdate($header, $text, $additional, $linkname, $name) {
        $this->libLoad('htmlpurifier');
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'header',
                'value' => $header,
                'field'=> 'Ф.И.О.',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'rang',
                'value' => $additional
            ),
            array(
                'type'  => 'string',
                'name'  => 'linkname',
                'value' => $linkname
            )
        );

        // Проверка вставляемого текста на XSS
        $settings = HTMLPurifier_Config::createDefault();
        $text = $this->toutf8($text);
        $text = $this->htmlpurifier->purify($text , $settings);
        $text = $this->to1251($text);
        
        if($this->validator->analyze($params)) {
            if(empty($text) || strlen($text) < 7) {
                $this->validator->filtered['lectext'] = $text;
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Не заполнен текст';
                return $this->validator->errors;
            }
            if(empty($this->validator->linkname)) {
                $linkname = $this->fileRename($this->validator->name);
            } else {
                $linkname = $this->fileRename($this->validator->linkname);
            }
            $lectpage = Page::find_by_linkname($this->validator->header);
            if($lectpage) {
                $lectpage->header = $this->validator->header;
                $lectpage->text = $text;
                $lectpage->additional = $this->validator->rang;
                $lectpage->linkname = $linkname;
                return $lectpage->save();
            } else {
                return false;
            }
        } else {
            // Возвращение сообщений, если в полученных данных была ошибка
            $this->validator->filtered['lectext'] = $text;
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

        // Редактирование новости
    public function lecturerEdit($name) {
        $lecpage = Page::find_by_linkname($name);
        return $lecpage;
    }

// Удаление страницы преподавателя
    public function lecturerDelete($name) {
        $lecturer = Page::first(array('type' => 'prepodavateli', 'linkname' => $name));
        return $lecturer->delete();
    }

    // Редактирование новости
    public function newsEdit($id) {
        $news_item = News::find_by_id($id);
        return $news_item;
    }
// Обновление новости
    public function newsUpdate($name, $announce, $newstext, $id) {
        $this->libLoad('htmlpurifier');
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $name,
                'field' => 'Заголовок',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'announce',
                'value' => $announce,
                'field' => 'Анонс',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'news_id',
                'value' => $id,
                'field' => 'Номер новости'
            )
        );
        
        // Проверка вставляемого текста на XSS
        $settings = HTMLPurifier_Config::createDefault();
        $newstext = $this->toutf8($newstext);
        $newstext = $this->htmlpurifier->purify($newstext , $settings);
        $newstext = $this->to1251($newstext);
        $news_item = News::find($id);

        if($this->validator->analyze($params)) {
            if(empty($newstext) || strlen($newstext) < 7) {
                $this->validator->errors['message'] = 'Не заполнен текст новости';
                $this->setAnswer($this->validator->filtered);
                return $this->validator->errors;
            }
        } else {
            // Возвращение сообщений, если в полученных данных была ошибка
            $this->validator->filtered['newstext'] = $newstext;
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
        
        $news_item->name = $this->validator->name;
        $news_item->announce = $this->validator->announce;
        $news_item->text = $newstext;
        return $news_item->save();
    }

    public function documentUpdate(array $post, $id) {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $post['name'],
                'field' => 'Название',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'comment',
                'value' => $post['comment']
            ),
            array(
                'type'  => 'bool',
                'name'  => 'important',
                'value' => $post['important']
            ),
            array(
                'type'  => 'int',
                'name'  => 'id',
                'value' => $id
            ),
            array(
                'type'  => 'int',
                'name'  => 'folder',
                'value' => $post['folder']
            ),
            array(
                'type'  => 'string',
                'name'  => 'link',
                'value' => $post['link']
            )

        );
        if($this->validator->analyze($params)) {
            $document = Document::find($this->validator->id);
            $document->name = $this->validator->name;
            $document->comment = $this->validator->comment;
            $document->important = $this->validator->important;
            $document->save();
            return true;
        } else {
            // Возвращение сообщений, если в полученных данных была ошибка
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    // Редактирование документа
    public function documentEdit($id) {
        $document = Document::find_by_id($id);
        return $document;
    }

// Проверка идентификатора новости
    public function idCheck($param) {
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

// Вывод списка страниц
    public function pages($max_page, $page, $link) {
        $this->libLoad('paginator');
        return $this->paginator->build($max_page, $page, $link);
    }

// Вывод списка записей гостевой книги
    public function guestList($offset = 0, $num = 0) {
        if($num) {
            $guest = Guestrecords::find('all', array('order' => 'writetime desc', 'offset' => $offset, 'limit' => $num));
        } else {
            $guest = Guestrecords::find('all', array('order' => 'writetime desc'));
        }
        foreach($guest as $key => $temp) {
            $guest[$key]->message = substr($temp->message, 0, 45);
        }
        return $guest;
    }

// Удаление записи из гостевой книги
    public function guestRecordDelete($id) {
        $news_item = Guestrecords::find($id);
        return $news_item->delete();
    }

// Сканирование документов и запись информации в базу данных
// Во вложенных папках не должна присутствовать папка docs
// Также первоначально следует помещать в папку файлы с нормальным названием для правильной записи в базу данных
    public function documentScan($path, $folder) {
        $this->basepath = $path;
        $filelist = array();
        $this->treeGet($path.DS.$folder, $filelist);
        $this->restruct($filelist, $folder, '');
        $struct_count = count($this->files);
        if(!empty($this->files)) {
            $database_docs = $this->documentsFromBase();
            // Поиск одинаковых значений, чтобы не удалять старые записи
            foreach($database_docs as $key => $doc) {
                foreach($this->files as $num => $file) {
                    if($doc->filename == $file['transl'] && $doc->parent == $file['parent']) {
                        unset($this->files[$num]);
                        unset($database_docs[$key]);
                    }
                }
            }
            $files_count  = count($this->files);
            $this->extraClear($database_docs);
            $this->documentSave($this->files);
            $this->setAnswer('Сканирование документов прошло успешно. '.$struct_count.' объектов просканировано, '.$files_count.' сохранено в базу данных');
            return true;
        }
        $this->setAnswer('При сканировании документов произошла ошибка, либо папка с документами пуста');
        return false;
    }

    public function validName($name) {
        $check = array(
            array(
                'type' => 'string',
                'name' => 'name',
                'value' => $name
            )
        );
        $this->validator->analyze($check);
        return $this->validator->name;
    }

    public function allFiles($type = 0) {
        return Document::find('all', array('folder' => $type));
    }

    // Вывод списка документов
    public function documentsList($offset = 0, $num = 0) {
        if($num) {
            $documents = Document::find('all', array('order' => 'addtime desc', 'offset' => $offset, 'limit' => $num, 'conditions' => 'folder = 0'));
        }
        return $documents;
    }

    public function getLecturer() {
        return Page::find('all', array('type' => 'prepodavateli'));
    }

    public function pageEdit($type) {
        $check = array(
            array (
                'name' => 'type',
                'type' => 'string',
                'value' => $type
            )
        );
        $this->validator->analyze($check);
        $page = Page::find_by_type($this->validator->type);
        if(!$page) {
            $page = new Page(array('type' => $this->validator->type));
            $page->save();
        }
        return $page;
    }

    // Обновление старницы
    public function pageUpadate($type, $header, $text) {
        $check = array(
            array (
                'name'  => 'type',
                'type'  => 'string',
                'value' => $type
            ),
            array (
                'name'  => 'header',
                'type'  => 'string',
                'value' => $header
            )
        );
        $this->validator->analyze($check);
        $this->libLoad('htmlpurifier');
        $page = Page::find_by_type($this->validator->type);
        if($page) {
            $settings = HTMLPurifier_Config::createDefault();
            $text = $this->toutf8($text);
            $text = $this->htmlpurifier->purify($text , $settings);
            $text = $this->to1251($text);
            $page->header = $this->validator->header;
            $page->text   = $text;
            $page->save();
            return true;
        } else {
            return false;
        }
    }

     // Получение настроек для сайта
    public function settingsGet() {
        $settings = Settings::first();
        if(empty($settings)) $settings = new Settings();
        return $settings;
    }

    public function changeSettings(array $post) {
        $maxnews  = $this->idCheck($post['maxnews']);
        $maxguest = $this->idCheck($post['maxguest']);
        $maxdocs  = $this->idCheck($post['maxdocs']);
        $news_limit  = $this->idCheck($post['news_limit']);
        $guest_limit  = $this->idCheck($post['guest_limit']);
        $files_limit  = $this->idCheck($post['files_limit']);

        $settings_record = $this->settingsGet();
        
        $settings_record->maxnews = $maxnews;
        $settings_record->maxguest = $maxguest;
        $settings_record->maxdocs = $maxdocs;
        $settings_record->news_limit = $news_limit;
        $settings_record->guest_limit = $guest_limit;
        $settings_record->files_limit = $files_limit;
        $settings_record->save();
        return true;
    }

    // Обоновление "куков" если предыдущие значения совпали
    private function cookieUpdate(Admin $admin, $timechange = 0) {
        $dif_time = 60 * 60;
        $word = $this->generateWord();
        $newkey = md5($admin->name . $admin->password . $word);
        $newtime = time() + $dif_time;
        $admin->authkey = $newkey;
        if($timechange) {
            $admin->timeleft = date('Y-m-d H:i:s', $newtime);
        }
        if(!empty($this->ip)) $admin->ip = $this->ip;
        $admin->save();
        return array('newkey' => $newkey, 'newtime' => time());
    }

// Генерация случайног слова для генерации значения в "куках"
    private function generateWord($len = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for($i = 0; $i < $len; $i++) {
            $pos = rand(0, strlen($chars) - 1);
            $string .= $chars{$pos};
        }
        return $string;
    }

// Сравнение введенного пароля с паролем в базе данных
    private function passCompare($ins_pass, $db_pass) {
        $check = md5($ins_pass . $this->salt);
        return $check == $db_pass ? true : false;
    }

    // Удаление записей из базы для которых нет совпадения с документами в папках
    private function extraClear(array $records) {
        foreach($records as $record) {
            $record->delete();
        }
    }

// Сохранение в базу данных документов
    private function documentSave(array $docs) {
        $this->ormLoad();
        foreach($docs as $doc) {
            $attr = array('name' => $doc['native'], 'filename' => $doc['transl'], 'parent' => $doc['parent'], 'size' => 0, 'link' => $doc['link'], 'folder' => 0);
            if($doc['type'] == 'file') $attr['size'] = $doc['size'];
                else $attr['folder'] = 1;
            Document::create($attr);
        }
    }

// Получение документов из базы данных
    private function documentsFromBase() {
        $this->ormLoad();
        $documents = Document::all();
        return $documents;
    }

    private function restruct(array $tree, $parent_name, $link) {
        $link = $link.'/'.$parent_name;
        foreach($tree as $parent => $branch) {
            if(is_array($branch)) {
                if(empty($branch)) continue;
                else {
                    // Для корректного переименования в windows и linux
                    $rename = $parent;
                    if($this->checkUtf($parent)) $parent = $this->to1251($parent);

                    $transl = $this->fileRename($parent);
                    $this->files[] = array(
                        'type'   => 'folder',
                        'native' => $parent,
                        'transl' => $transl,
                        'parent' => $parent_name,
                        'link'   => $link
                    );
                    // Переименование папки в транслит
                    @rename($this->basepath.$link.'/'.$rename, $this->basepath.$link.'/'.$transl);
                    // И продолжение скаинрования уже с новым именем
                    $this->restruct($branch, $transl, $link);
                }
            } else {
                if(!empty($branch)) {
                    // Для корректного переименования в windows и linux
                    $rename = $branch;
                    if($this->checkUtf($branch)) $branch = $this->to1251($branch);
                    
                    $transl = $this->fileRename($branch);
                    // Переименование файла в транслит
                    @rename($this->basepath.$link.'/'.$rename, $this->basepath.$link.'/'.$transl);
                    // Получение размера, если это файл
                    $size = filesize($this->basepath.$link.'/'.$transl);
                    $this->files[] = array(
                        'type'   => 'file',
                        'native' => $branch,
                        'transl' => $transl,
                        'parent' => $parent_name,
                        'link'   => $link,
                        'size'   => $size
                    );
                }
            }
        }
        return true;
    }

    // Строит дерево каталогов с файлами за исключением пустых папок
    private function treeGet($path, & $folder) {
        $folder = scandir($path);
        foreach($folder as $key => $file) {
            if($file == '.' || $file == '..' || $file == '.htaccess') {
                unset($folder[$key]);
                continue;
            }
            if(is_dir($path . DS . $file)) {
                unset($folder[$key]);
                $this->treeGet($path . DS . $file, $folder[$file]);
            } else {
                if(!is_array($file)) {
                    $folder[$key] = $file;
                }
            }
        }
        return $folder;
    }

    private function fileRename($name) {
        $tbl = array(
            'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
            'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
            'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A',
            'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I',
            'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
            'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>"yo", 'х'=>"h",
            'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ъ'=>"b", 'ь'=>"b", 'ю'=>"yu", 'я'=>"ya",
            'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH", 'Ъ'=>"", 'Ь'=>"",
            'Ю'=>"YU", 'Я'=>"YA", ' ' => '_'
        );

        $len = strlen($name);
        for($i = 0; $i < $len; $i++) {
            if(array_key_exists($name[$i], $tbl)) $name[$i] = $tbl{$name[$i]};
        }
        return $name;
    }

    private function newsSubscribers($newsid, $header, $text) {
        @ini_set('max_execution_time', 0);
        $mail_list = Subscription::find('all', array('enabled' => 1));
        if(count($mail_list) > 0) {
            $hostname = Cfg::inst()->get('hostname');
            $text = wordwrap($text, 100, "\n");
            $newstext  = "Название новости: {$header}\n\n";
            $newstext .= $text."\n\n";
            $newstext .= "Читать далее {$hostname}news/{$newsid}/\n";
            foreach($mail_list as $user) {
                $send = $newstext;
                $send .= "\nЧтобы отписаться от рассылки новостей пройдите по ссылке:\n";
                $send .= "{$hostname}mailboxunsubscribe/".$user->stopkey."/\n";
                $send .= "===========================================================================================================\n";
                $send .= "C наилучшими пожеланиями, Администрация {$hostname}";
                $this->sendMail($user->email, 'Новости kipr.susu.ac.ru', $send);
                $send = "";
            }
            return true;
        }
        return false;
    }

    private function sendMail($address, $subject, $text) {
        $nameFrom  = 'Admin';
        $emailFrom = $this->frombox;
        $subject   = "=?windows-1251?b?" . base64_encode($subject) . "?=";
        $headers   = 'Content-Type: text/plain; charset="windows-1251"' . "\r\n" .
                     'Content-Transfer-Encoding: 8bit' . "\r\n" .
                     'From: =?windows-1251?b?' . base64_encode($nameFrom) . '?= <' . $emailFrom . '>';
        $res = mail($address, $subject, $text, $headers, "-f$emailFrom");
    }

    private function to1251($str) {
        return iconv('utf-8', 'windows-1251', $str);
    }

    private function toutf8($str) {
        return iconv('windows-1251', 'utf-8', $str);
    }

    public function checkWin($str) {
        return mb_check_encoding($str, 'windows-1251');
    }

     public function checkUtf($str) {
        return mb_check_encoding($str, 'utf-8');
    }

     public function commentDelete($id) {
        $comment = Newscomment::find_by_id($id);
        if(!$comment) return false;
        $comment->delete();
        return true;
    }

    public function setAnswer($value) {
        $this->answer = $value;
    }
    public function getAnswer() {
        return $this->answer;
    }


    /**
     * Функции для администрирования форума
     */

    public function getCategory($id) {
        try {
            return Forumcategory::find($id);
        } catch(Exception $e) {
            return false;
        }
    }

     public function categoryInsert(array $post) {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $post['name'],
                'field' => 'Название',
                'req'   => 1
            ),
            array(
                'type' => 'int',
                'name' => 'pos',
                'value' => $post['pos'],
                'field' => 'Позиция',
                'req'   => 1,
                'options' => array(
                    'options' => array(
                        'default' => 500,
                    )
                )
            ),
            array(
                'type'  => 'string',
                'name'  => 'logo',
                'value' => $post['logo'],
                'field' => 'Краткое описание',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'rule',
                'value' => $post['rule']
            ),
            array(
                'type'  => 'bool',
                'name'  => 'hide',
                'value' => $post['hide'],
            )
        );

        if($this->validator->analyze($params)) {
            if(empty($this->validator->filtered['hide'])) $this->validator->filtered['hide'] = false;
            $category = new Forumcategory();
            $category->name = $this->validator->filtered['name'];
            if(!empty($this->validator->rule)) $category->rule = $this->validator->filtered['rule'];
            $category->logo = $this->validator->filtered['logo'];
            $category->pos = $this->validator->filtered['pos'];
            $category->hide = $this->validator->filtered['hide'] == false ? "hide" : "show";
            // Чтобы было всегда куда перенаправить пользователя в случае неправильного адреса
            $firstcheck = Forumcategory::find_by_id_forum(1);
            if(empty($firstcheck)) $category->id_forum = 1;
            // Добавление нового форума
            $category->save();
            $this->categoryLasttime($category, 'add');
            return true;
        } else {
            if(empty($this->validator->filtered['hide'])) $this->validator->filtered['hide'] = false;
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    public function categoryUpdate(array $post, $id) {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $post['name'],
                'field' => 'Название',
                'req'   => 1
            ),
            array(
                'type' => 'int',
                'name' => 'pos',
                'value' => $post['pos'],
                'field' => 'Позиция',
                'req'   => 1,
                'options' => array(
                    'options' => array(
                        'default' => 500,
                    )
                )
            ),
            array(
                'type'  => 'string',
                'name'  => 'logo',
                'value' => $post['logo'],
                'field' => 'Краткое описание',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'rule',
                'value' => $post['rule']
            ),
            array(
                'type'  => 'bool',
                'name'  => 'hide',
                'value' => $post['hide'],
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_forum',
                'value' => $id
            ),
        );

        if($this->validator->analyze($params)) {
            if(empty($this->validator->filtered['hide'])) $this->validator->filtered['hide'] = false;
            $category = $this->getCategory($this->validator->id_forum);
            if(empty($category)) {
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Данного раздела не существует';
                return $this->validator->errors;
            }
            $category->name = $this->validator->filtered['name'];
            if(!empty($this->validator->rule)) $category->rule = $this->validator->filtered['rule'];
            $category->logo = $this->validator->filtered['logo'];
            $category->pos = $this->validator->filtered['pos'];
            $category->hide = $this->validator->filtered['hide'] == false ? "hide" : "show";
            // Добавление нового форума
            $category->save();
            return true;
        } else {
            if(empty($this->validator->filtered['hide'])) $this->validator->filtered['hide'] = false;
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    // Удаление всего раздела форума
    public function categoryDelete($id) {
        $category = Forumcategory::find_by_id_forum($id);
        if(empty($category)) return false;
        $themes = Forumtheme::all(array('conditions' => 'id_forum ='.$category->id_forum));
        if(!empty($themes)) {
            $ids = array();
            foreach ($themes as $key => $theme) {
                $ids[] = $theme->id_theme;
                $theme->delete();
            }
            $ids = implode(',', $ids);
            $messages = Forumpost::all(array('conditions' => "id_theme IN({$ids})"));
            if(!empty($messages)) {
                foreach($messages as $key => $message) {
                    $message->delete();
                }
            }
        }
        $this->categoryLasttime($category, 'delete');
        $category->delete();
        return true;
    }

    // Сокрытие раздела форума и всех его тем
    public function categoryHide($id, $mode = 1) {
        $word = $mode == 1 ? 'hide' : 'show';
        $category = Forumcategory::find_by_id_forum($id);
        if(empty($category))
            return false;
        $themes = Forumtheme::all(array('conditions' => 'id_forum =' . $category->id_forum));
        if(!empty($themes)) {
            foreach($themes as $key => $theme) {
                if($theme->hide != 'lock') {
                    $theme->hide = $word;
                    $theme->save();
                }
            }
        }
        $category->hide = $word;
        $category->save();
        return true;
    }

     public function categoryJoin(array $post, $id) {
        $params = array(
            array(
                'type'  => 'int',
                'name'  => 'forum',
                'value' => $post['forum'],
                'field' => 'Переместить раздел',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_forum',
                'value' => $id
            ),
        );

        if($this->validator->analyze($params)) {
            $forum = $this->getCategory($this->validator->forum);
            if(empty($forum) || $forum->id_forum == $this->validator->id_forum) {
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Форума с которым проводится объединение не существует';
                return $this->validator->errors;
            }
            $category = $this->getCategory($this->validator->id_forum);
            if(empty($category)) {
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Данного форума не существует';
                return $this->validator->errors;
            }
            $themes = Forumtheme::all(array('conditions' => 'id_forum =' . $this->validator->id_forum));
            if(!empty($themes)) {
                foreach($themes as $key => $theme) {
                    $theme->id_forum = $forum->id_forum;
                    $theme->save();
                }
            }
            $this->categoryLasttime($category, 'delete');
            $category->delete();
            return true;
        } else {
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    public function categoriesWithout($id) {
        return Forumcategory::all(array('order' => 'pos asc', 'conditions' => "id_forum !={$id}"));
    }

    private function categoryLasttime(Forumcategory $category, $mode = 'add') {
        if($mode == 'add') {
            $category::connection()->query("ALTER TABLE forum_last_time ADD now{$category->id_forum} datetime NOT NULL , ADD last_time{$category->id_forum} datetime NOT NULL");
        } else {
            $category::connection()->query("ALTER TABLE forum_last_time DROP now{$category->id_forum}, DROP last_time{$category->id_forum}");
        }
    }

    public function themeHide($id, $mode = 'hide') {
        if($mode == 'hide') {
            $word = 'hide';
        } elseif($mode == 'lock') {
            $word = 'lock';
        } else {
            $word = 'show';
        }
        $theme = Forumtheme::find_by_id_theme($id);
        if(empty($theme)) return false;
        $posts = Forumpost::all(array('conditions' => 'id_theme =' . $theme->id_theme));
        if(!empty($posts)) {
            foreach($posts as $key => $post) {
                $post->hide = $word;
                $post->save();
            }
        }
        $theme->hide = $word;
        $theme->save();
        return true;
    }

     public function themeUpdate(array $post, $id = '', $theme = '') {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $post['name'],
                'field' => 'Название',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'author',
                'value' => $post['author'],
                'field' => 'Автор',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_theme',
                'value' => $id
            ),
            array(
                'type'  => 'int',
                'name'  => 'newforum',
                'value' => $post['newforum']
            ),
        );

        if($this->validator->analyze($params)) {
            if(empty($theme)) {
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Такой темы не существует';
                return $this->validator->errors;
            }
            if($theme->id_forum == $this->validator->newforum) {
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Тема уже находится в данном разделе';
                return $this->validator->errors;
            }
            if(!empty($this->validator->newforum)) {
                $res = Forumcategory::find_by_id_forum($this->validator->newforum);
                if(empty($res)) {
                    $this->setAnswer($this->validator->filtered);
                    $this->validator->errors['message'] = 'Раздела, в который перемещается тема, не существует';
                    return $this->validator->errors;
                }
            }

            $theme->name = $this->validator->name;
            $theme->author = $this->validator->author;
            if(!empty($this->validator->newforum)) $theme->id_forum = $this->validator->newforum;
            // Обновление темы
            $theme->save();
            return true;
        } else {
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    // Получение темы
    public function getTheme($id) {
        try {
            return Forumtheme::find($id);
        } catch(Exception $e) {
            return false;
        }
    }

    // Получение сообщения
    public function getPost($id) {
        try {
            return Forumpost::find($id);
        } catch(Exception $e) {
            return false;
        }
    }

    public function postHide(Forumpost $post) {
        $child_posts = Forumpost::all(array('conditions' => array('parent_post =?', $post->id_post)));
        if(!empty($child_posts)) {
            foreach($child_posts as $key => $child) {
                $this->postHide($child);
            }
        }
        $post->hide = 'hide';
        $post->save();
        return true;
    }

    public function postAction($id, $word) {
        $post = Forumpost::find_by_id_post($id);
        if(!empty($post)) {
            $post->hide = $word;
            $post->save();
            return true;
        }
        return false;
    }

     public function postUpdate(array $post, $id = '') {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'name',
                'value' => $post['name'],
                'field' => 'Сообщение',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'author',
                'value' => $post['author'],
                'field' => 'Автор',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_post',
                'value' => $id
            )
        );

        if($this->validator->analyze($params)) {
            $forum_message = Forumpost::find_by_id_post($this->validator->id_post);
            if(empty($forum_message)) {
                $this->setAnswer($this->validator->filtered);
                $this->validator->errors['message'] = 'Такого сообщения не существует';
                return $this->validator->errors;
            }

            $forum_message->name = $this->validator->name;
            $forum_message->author = $this->validator->author;
            // Обновление сообщения
            $forum_message->save();
            return true;
        } else {
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    // Обновление времени темы и человека, писавшего в тему
    public function themeTime($id_theme) {
        $theme = Forumtheme::find_by_id_theme($id_theme);
        if(!empty($theme)) {
            $theme->time = date('Y-m-d H:i:s');
            $theme->last_author = 'Администратор';
            $theme->save();
            return true;
        }
        return false;
    }

    public function userStatus($id, $mode = 'ordinary') {
        $word = $mode == 'administrator' ? 'admin' : '';
        $user = $this->getUser($id);
        if($user) {
           $user->statususer = $word;
           $user->save();
           return true;
        }
        return false;
    }

    public function getUser($id) {
        try {
            return Forumauthor::find($id);
        } catch(Exception $e) {
            return false;
        }
    }

    public function userUpdate(array $post, $id = '') {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'username',
                'value' => $post['username'],
            ),
            array(
                'type'  => 'string',
                'name'  => 'photo_url',
                'value' => $post['photo_url'],
            ),
            array(
                'type'  => 'bool',
                'name'  => 'photo',
                'value' => $post['photo']
            ),
            array(
                'type'  => 'string',
                'name'  => 'pass',
                'value' => $post['pass'],
            ),
            array(
                'type'  => 'email',
                'name'  => 'email',
                'value' => $post['email'],
                'field' => 'E-mail'
            ),
            array(
                'type'  => 'int',
                'name'  => 'icq',
                'value' => $post['icq'],
                'field' => 'ICQ'
            ),
            array(
                'type'  => 'url',
                'name'  => 'url',
                'value' => $post['url'],
                'field' => 'URL'
            ),
            array(
                'type'  => 'string',
                'name'  => 'about',
                'value' => $post['about'],
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_author',
                'value' => $id
            ),
            array(
                'type'  => 'int',
                'name'  => 'statususer',
                'value' => $post['statususer'],
                'options' => array(
                    'options' => array(
                        'default' => 1,
                    )
                )
            ),
            array(
                'type'  => 'int',
                'name'  => 'themes',
                'value' => $post['themes'],
                'options' => array(
                    'options' => array(
                        'default' => 0,
                    )
                )
            )
        );

        if($this->validator->analyze($params)) {
            $this->setAnswer($this->validator->filtered);
            if($this->validator->pass != $post['passagain']) {
                $this->validator->errors['message'] = 'Введенные пароли не совпадают';
                return $this->validator->errors;
            }
            $user = $this->getUser($this->validator->id_author);
            if(!$user) {
                $this->validator->errors['message'] = 'Редактируемого пользователя не существует';
                return $this->validator->errors;
            }
            if($this->validator->statususer == 2) {
                $user->statususer = 'admin';
            } else {
                $user->statususer = '';
            }
            if($this->validator->photo) {
                $this->deletePhoto($user->photo);
                $user->photo = '';
            }
            if(!empty($this->validator->pass)) $user->passw = $this->passwordGenerate($this->validator->pass);
            $user->email = $this->validator->email;
            $user->themes = $this->validator->themes;
            $user->url = $this->validator->url;
            $user->icq = $this->validator->icq;
            $user->about = $this->validator->about;
            $user->save();
            return true;
        } else {
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    public function settingsUpdate(array $post) {
        $params = array(
            array(
                'type'  => 'string',
                'name'  => 'nameforum',
                'value' => $post['nameforum'],
                'field' => 'Название форума',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'numberthemes',
                'value' => $post['numberthemes'],
                'field' => 'Количество тем на странице',
                'req'   => 1,
                'options' => array(
                    'options' => array(
                        'default' => 15,
                    )
                )
            ),
            array(
                'type'  => 'int',
                'name'  => 'cooktime',
                'value' => $post['cooktime'],
                'options' => array(
                    'options' => array(
                        'default' => 1,
                    )
                )
            ),
            array(
                'type'  => 'bool',
                'name'  => 'avatar',
                'value' => $post['avatar']
            )
        );

        if($this->validator->analyze($params)) {
            $settings = $this->settingsGet();
            $settings->forum_name = $this->validator->nameforum;
            $settings->forum_themes = $this->validator->numberthemes;
            $settings->forum_cooktime = $this->validator->cooktime;
            $settings->forum_photoload = $this->validator->avatar;
            $settings->save();
            return true;
        } else {
            $this->setAnswer($this->validator->filtered);
            return $this->validator->errors;
        }
    }

    public function userDelete($id) {
        $user = $this->getUser($id);
        if($user) {
            if(!empty($user->photo)) $this->deletePhoto($user->photo);
            $user->forumlasttime->delete();
            $user->delete();
            return true;
        }
        return false;
    }

    // Генерация пароля для пользователя
    public function passwordGenerate($pass) {
        return md5($this->forum_salt.$pass);
    }

    // Удаление фото пользователя форума
    private function deletePhoto($src = '') {
        if(!empty($src)) {
            @unlink(ROOT . $src);
            return true;
        }
        return false;
    }
}