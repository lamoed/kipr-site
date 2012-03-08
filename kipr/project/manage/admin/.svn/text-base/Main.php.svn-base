<?php

class Main extends MainController
{
    public $news_limit = 20;

// Проверка учетной записи администратора при каждом действии
    function Head() {
        // Добавление загрузчика библиотек при каждом обращении к модели раздела администратора
        $this->attachModelEvent('libsLoad');
        // Один заголовок для всех страниц раздела администирования,
        // т.к. он все равно не должен индексироваться поисковыми системами
        $this->view->setTitle('Раздел администратора');
        if(!empty($this->data->post['nick']) && !empty($this->data->post['password'])) {
            $this->mdl()->ip = $this->data->server['REMOTE_ADDR'];
            $result = $this->mdl()->dbuserCheck($this->data->post['nick'], $this->data->post['password']);
            if($result) {
                setcookie('key', $result['newkey'], 0, '/');
                setcookie('timeleft', $result['newtime'], 0, '/');
            } else {
                // Для предотвращения автоматического продолжения скрипта без влияния результата обработки
                $this->forward($this, 'login');
            }
        } else {
            if(empty($this->data->cookie['key']) || empty($this->data->cookie['timeleft'])) {
                setcookie('key', '');
                setcookie('timeleft', '');
                $this->forward($this, 'login');
            } else {
                $result = $this->mdl()->dbcookieCheck($this->data->cookie['key'], $this->data->cookie['timeleft']);
                if($result) {
                    setcookie('key', $result['newkey'], 0, '/');
                    setcookie('timeleft', $result['newtime'], 0, '/');
                } else {
                    setcookie('key', '');
                    setcookie('timeleft', '');
                    $this->forward($this, 'login');
                }
            }
        }
    }
    
    public function First() {
        $this->view->setLayout('adminlayout');
        $this->settings = $this->view->settings = $this->mdl()->dbsettingsGet();
        $this->view->render('admin/index');
    }

// Вывод формы входа для администратора
    public function login() {
        $this->view->message = $this->mdl()->getAnswer();
        $this->view->setLayout('adminlayout');
        $this->view->render('admin/loginform');
    }

// Функция осуществления всех действий над новостями
    public function news($action = 'default', $number = '') {
        $this->view->setLayout('adminlayout');
        $page = $this->mdl()->idCheck($number);
        switch($action) {
            case 'add':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->newsInsert($this->data->post['name'], $this->data->post['announce'],
                                        $this->data->post['newstext']);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->javascript = $this->mdl()->connectCk('newstext');
                        $this->view->render('admin/newsaddform');
                    } else {
                        $this->redirect('/admin/news/');
                    }
                }
                $this->view->javascript = $this->mdl()->connectCk('newstext');
                $this->view->render('admin/newsaddform');
                break;
            // Редактирование новости
            case 'edit':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->newsUpdate($this->data->post['name'], $this->data->post['announce'],
                                        $this->data->post['newstext'], $page);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->javascript = $this->mdl()->connectCk('newstext');
                        $this->view->render('admin/newseditform');
                    } else {
                        $this->redirect('/admin/news/');
                    }
                }
                $this->view->news_item = $this->mdl()->newsEdit($page);
                $this->view->javascript = $this->mdl()->connectCk('newstext');
                $this->view->render('admin/newseditform');
                break;
            // Удаление новости, если введено неправильное значение то по умолчанию удалит первую новость
            case 'delete':
                $this->mdl()->newsDelete($page);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            // Постраничный показ всего списка новостей
            case 'page':
                $settings = $this->mdl()->dbsettingsGet();
                $num = $this->mdl()->countBigger('news', $settings->news_limit);
                if($num) {
                    // когда новостей больше 20ти
                    // загрузить paginator и вместе с ним вывести список новостей
                    $max_page = ceil($num / $settings->news_limit) - 1;
                    $page -= 1;
                    if($page < 1 || $page > $max_page) $page = 0;
                    $begin = $page * $settings->news_limit;
                    $this->view->news = $this->mdl()->newsList($begin, $settings->news_limit);
                    $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/admin/news/page');
                    $this->view->render('admin/newslist');
                } else {
                    $this->view->news = $this->mdl()->newsList();
                    $this->view->render('admin/newslist');
                }
                break;
            default:
                $num = $this->mdl()->countBigger('news', $settings->news_limit);
                if($num) {
                    // когда новостей больше 30ти
                    $this->news('page', 1);
                } else {
                    $this->view->news = $this->mdl()->newsList();
                    $this->view->render('admin/newslist');
                }
                break;
        }
    }
    
    public function guestbook($action = 'default', $number = '') {
        $this->view->setLayout('adminlayout');
        $page = $this->mdl()->idCheck($number);
        switch($action) {
            // Удаление записи, если введено неправильное значение то по умолчанию удалит первую запись
            case 'delete':
                $this->mdl()->guestRecordDelete($page);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            // Постраничный показ всего списка записей гостевой книги
            case 'page':
                $settings = $this->mdl()->dbsettingsGet();
                $num = $this->mdl()->countBigger('guestrecords', $settings->guest_limit);
                if($num) {
                    // когда записей в гостевой книге больше 15ти
                    // загрузить paginator и вместе с ним вывести список записей гостевой книги
                    $max_page = ceil($num / $settings->guest_limit) - 1;
                    $page -= 1;
                    if($page < 1 || $page > $max_page) $page = 0;
                    $begin = $page * $settings->guest_limit;
                    $this->view->records = $this->mdl()->guestList($begin, $settings->guest_limit);
                    $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/admin/guestbook/page');
                    $this->view->render('admin/guestmessages');
                } else {
                    $this->view->records = $this->mdl()->guestList();
                    $this->view->render('admin/guestmessages');
                }
                break;
            default:
                $num = $this->mdl()->countBigger('guestrecords', $settings->guest_limit);
                if($num) {
                    // когда записей в гостевой книге больше 15ти
                    $this->guestbook('page', 1);
                } else {
                    $this->view->records = $this->mdl()->guestList();
                    $this->view->render('admin/guestmessages');
                }
                break;
        }
    }

    // Редактирование и сканирование документов
    public function documents($action = 'default', $param = '') {
        $this->view->setLayout('adminlayout');
        switch($action) {
            case 'scan':
                $this->mdl()->dbdocumentScan(ROOT, 'docs');
                $this->view->message_str = $this->mdl()->getAnswer();
                $this->forward($this, 'documents');
                break;
            case 'files':
                $this->view->setLayout('adminlayout');
                $settings = $this->mdl()->dbsettingsGet();
                $num = $this->mdl()->countBiggerCondition('document', array('conditions' => 'folder = 0'), $settings->files_limit);
                if($num) {
                    $page = $this->mdl()->idCheck($param);
                    $max_page = ceil($num / $settings->files_limit) - 1;
                    $page -= 1;
                    if($page < 1 || $page > $max_page) $page = 0;
                    $begin = $page * $settings->files_limit;
                    $this->view->files = $this->mdl()->dbdocumentsList($begin, $settings->files_limit);
                    $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/admin/documents/files');
                    $this->view->render('admin/files');
                } else {
                    $this->view->files = $this->mdl()->dballFiles(0);
                    $this->view->render('admin/files');
                }
                break;
            case 'folders':
                $this->view->files = $this->mdl()->dballFiles(1);
                $this->view->setLayout('adminlayout');
                $this->view->render('admin/files');
                break;
            case 'edit':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->documentUpdate($this->data->post, $param);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $answer = $this->mdl()->getAnswer();
                        $this->view->answer = $answer;
                        $answer['folder'] == 0 ? $this->view->render('admin/documentform') : $this->view->render('admin/folderform');
                    } else {
                        $this->redirect('/admin/documents/');
                    }
                } else {
                    $document = $this->mdl()->documentEdit($param);
                    $this->view->document = $document;
                    $document->folder == 0 ? $this->view->render('admin/documentform') : $this->view->render('admin/folderform');
                }
                break;
            default:
                $this->view->render('admin/documents');
                break;
        }
    }


    // Добавление и редактирование страниц преподавателей
    public function lecturers($action = 'default', $param = '') {
        $this->view->setLayout('adminlayout');
        switch($action) {
            case 'add':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->dblecturerInsert($this->data->post['header'], $this->data->post['lectext'],
                                        $this->data->post['additional'], $this->data->post['linkname']);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->javascript = $this->mdl()->connectCk('lectext');
                        $this->view->render('admin/lecaddform');
                    } else {
                        $this->redirect('/admin/lecturers/');
                    }
                }
                $this->view->javascript = $this->mdl()->connectCk('lectext');
                $this->view->render('admin/lecaddform');
                break;
            case 'edit':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->dblecturerUpdate($this->data->post['header'], $this->data->post['lectext'],
                                        $this->data->post['additional'], $this->data->post['linkname'], $param);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->javascript = $this->mdl()->connectCk('lectext');
                        $this->view->render('admin/lecturereditform');
                    } else {
                        $this->redirect('/admin/lecturers/');
                    }
                } else {
                    $this->view->lecturer = $this->mdl()->dblecturerEdit($param);
                    $this->view->javascript = $this->mdl()->connectCk('lectext');
                    $this->view->render('admin/lecturereditform');
                }
                break;
            case 'delete':
                $this->mdl()->dblecturerDelete($param);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            default:
                $this->view->lecturers = $this->mdl()->dbgetLecturer();
                $this->view->render('admin/lecturerslist');
                break;
        }
    }

    // Изменение настроек сайта
    public function changeSettings() {
        if(!empty($this->data->post)) {
            $this->mdl()->dbchangeSettings($this->data->post);
        }
        $this->redirect('/admin/');
    }

    // Информационные страницы
    public function info($action = 'default') {
        $this->view->setLayout('adminlayout');
        switch($action) {
            case 'student':
                $this->page('student', 'студенту');
                break;
            case 'abiturient':
                $this->page('abiturient', 'абитуриенту');
                break;
            case 'speciality':
                $this->page('speciality', 'специальности');
                break;
            case 'contacts':
                $this->page('contacts', 'контакты');
                break;
            case 'publications':
                $this->page('publications', 'публикации');
                break;
            case 'patents':
                $this->page('patents', 'патенты');
                break;
            case 'exhibition':
                $this->page('exhibition', 'выставки');
                break;
            case 'awards':
                $this->page('awards', 'награды');
                break;
            default:
                $this->redirect('/admin/');
                break;
        }
    }

     // Добавление и редактирование страниц преподавателей
    public function comments($action = 'default', $param = '') {
        $param = $this->mdl()->idCheck($param);
        $this->view->setLayout('adminlayout');
        switch($action) {
            case 'delete':
                $this->mdl()->dbcommentDelete($param);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            default:
                $this->redirect('/admin/news/');
                break;
        }
    }

    // Функция добавления и обновления страницы
    private function page($type, $name) {
        if(!empty($this->data->post)) {
            $this->mdl()->dbpageUpadate($type, $this->data->post['header'], $this->data->post['text']);
            $this->redirect('/admin/');
        }
        $this->view->page = $this->mdl()->dbpageEdit($type);
        $this->view->link = $type;
        $this->view->type = $name;
        $this->view->javascript = $this->mdl()->connectCk('text');
        $this->view->render('admin/pageaddform');
    }

    /**
     * Функции управления форумом
     */


    public function Index() {
        $categories = $this->mdl('Forum')->dbgetCategories('all');
        if(!empty($categories)) {
            $first = $categories[0];
            $this->forward($this, 'Themes', '', array($first->id_forum));
        } else {
            $this->forward($this, 'Themes');
        }
    }

    public function Themes($id_forum = 1, $page = 1) {
        $id_forum = $this->mdl()->idCheck($id_forum);
        $this->view->selected = $id_forum;
        $page = $this->mdl()->idCheck($page);
        $categories = $this->mdl('Forum')->dbgetCategories('all');
        if(!empty($categories)) {
            $num = $this->mdl('Forum')->themeBigger($id_forum, $this->news_limit, 'all');

            if($num) {
                $max_page = ceil($num / $this->news_limit) - 1;

                $page -= 1;
                if($page < 1 || $page > $max_page)
                    $page = 0;
                $begin = $page * $this->news_limit;
                // Список страниц
                $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/admin/forum/themes/' . $id_forum);
                // Список тем
                $themes = $this->mdl('Forum')->getThemes($id_forum, $begin, $this->news_limit, 'all');
            } else {
                $themes = $this->mdl('Forum')->getThemes($id_forum, 0, 0, 'all');
            }

            if($themes) {
                $posts = $this->mdl('Forum')->themesPostcount($themes, 'all');
            }
            $this->view->categories = $categories;
            $this->view->themes = $themes;
            $this->view->posts = $posts;
        }
        $this->Forumcommon();
        $this->view->render('admin/forum/index');
    }

    public function Theme($action = '', $id = '') {
        $id = $this->mdl()->idCheck($id);
        $this->Forumcommon();
        switch($action) {
            case 'view':
                if(empty($id)) $id = 1;
                $theme = $this->mdl()->dbgetTheme($id);
                // Выделение форума, в котором находится тема
                if(!empty($theme)) $this->view->selected = $theme->id_forum;
                $data['posts'] = $this->mdl('Forum')->getPosts($id, 0, 'all');
                // Организация полученных постов в древовидную структуру
                $data['posts'] = $this->mdl('Forum')->organizePosts($data, 0, 0);
                $this->view->categories = $this->mdl('Forum')->dbgetCategories('all');
                $this->view->render('admin/forum/theme', $data);
                break;
            case 'edit':
                $theme = $this->mdl()->dbgetTheme($id);
                if(!empty($theme)) {
                    $categories = $this->mdl()->categoriesWithout($theme->id_forum);
                }
                $this->view->categories = $categories;
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->dbthemeUpdate($this->data->post, $id, $theme);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->render('admin/forum/themeeditform');
                    } else {
                        if(!empty($theme)) {
                            $this->redirect('/admin/forum/themes/'.$theme->id_forum);
                        } else {
                            $this->redirect('/admin/forum/themes/');
                        }
                    }
                } else {
                    $this->view->theme = $theme;
                    $this->view->render('admin/forum/themeeditform');
                }
                break;
            case 'hide':
                $this->mdl()->dbthemeHide($id, 'hide');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'show':
                $this->mdl()->dbthemeHide($id, 'show');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'close':
                $this->mdl()->dbthemeHide($id, 'lock');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            default:
                $this->redirect('/admin/forum/categories/');
                break;
        }
    }

    public function Post($action = '', $id = '') {
        $id = $this->mdl()->idCheck($id);
        $this->Forumcommon();
        switch($action) {
            case 'edit':
                $forum_message = $this->mdl()->dbgetPost($id);
                $params = $this->prepareParameters(2);
                $theme = $this->view->theme = $this->mdl()->idCheck($params['theme']);
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->dbpostUpdate($this->data->post, $id);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->render('admin/forum/posteditform');
                    } else {
                        $this->redirect('/admin/forum/theme/view/'.$theme);
                    }
                } else {
                    $this->view->post = $forum_message;
                    $this->view->render('admin/forum/posteditform');
                }
                break;
            case 'hide':
                $forum_message = $this->mdl()->dbgetPost($id);
                if(!empty($forum_message)) {
                    $this->mdl()->postHide($forum_message);
                    $this->mdl()->themeTime($forum_message->id_theme);
                }
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'show':
                $this->mdl()->dbpostAction($id, 'show');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'close':
                $this->mdl()->dbpostAction($id, 'lock');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            default:
                $this->redirect('/admin/forum/categories/');
                break;
        }
    }

    public function Categories() {
        $this->view->categories = $this->mdl('Forum')->getCategories('all');
        $this->Forumcommon();
        $this->view->render('admin/forum/categories');
    }

    public function Category($action = '', $id = '') {
        $this->Forumcommon();
        $id = $this->mdl()->idCheck($id);
        switch($action) {
            case 'add':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->dbcategoryInsert($this->data->post);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->render('admin/forum/categoryaddform');
                    } else {
                        $this->redirect('/admin/forum/categories/');
                    }
                }
                $this->view->render('admin/forum/categoryaddform');
                break;
            case 'edit':
                if(!empty($this->data->post)) {
                    $result = $this->mdl()->dbcategoryUpdate($this->data->post, $id);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->render('admin/forum/categoryeditform');
                    } else {
                        $this->redirect('/admin/forum/categories/');
                    }
                } else {
                    $this->view->category = $this->mdl()->dbgetCategory($id);
                    $this->view->render('admin/forum/categoryeditform');
                }
                break;
            case 'delete':
                $this->mdl()->dbcategoryDelete($id);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'hide':
                $this->mdl()->dbcategoryHide($id);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'show':
                $this->mdl()->dbcategoryHide($id, 2);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'join':
                  if(!empty($this->data->post)) {
                    $result = $this->mdl()->dbcategoryJoin($this->data->post, $id);
                    if($result !== true) {
                        $this->view->errors = $result;
                        $this->view->answer = $this->mdl()->getAnswer();
                        $this->view->render('admin/forum/categoryjoinform');
                    } else {
                        $this->redirect('/admin/forum/categories/');
                    }
                }
                $category = $this->mdl()->dbgetCategory($id);
                if($category) {
                    $this->view->category = $category;
                }
                $this->view->categories = $this->mdl()->dbcategoriesWithout($id);
                $this->view->render('admin/forum/categoryjoinform');
                break;
            default:
                $this->redirect('/admin/forum/categories/');
                break;
        }
    }

    public function Users($page = 0) {
        $this->Forumcommon();
        if(!empty($page)) {
            $page = $this->mdl()->idCheck($page);
        } else {
            $page = 1;
        }
        $this->view->page = $page;
        $num = $this->mdl('Forum')->dbusersBigger($this->news_limit);

        if($num) {
            $max_page = ceil($num / $this->news_limit) - 1;

            $page -= 1;
            if($page < 1 || $page > $max_page)
                $page = 0;
            $begin = $page * $this->news_limit;
            // Список страниц
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/admin/forum/users');
            // Список тем
            $this->view->users =  $this->mdl('Forum')->dbgetUsers($begin, $this->news_limit);
        } else {
            $this->view->users = $this->mdl('Forum')->dbgetUsers();
        }
        $this->view->render('admin/forum/users');
    }

    public function User($action = '', $id = '') {
        $id = $this->mdl()->idCheck($id);
        $this->Forumcommon();
        switch($action) {
            case 'edit':
                $user = $this->mdl()->dbgetUser($id);
                $params = $this->prepareParameters(2);
                $this->view->page = $page = $this->mdl()->idCheck($params['page']);
                if(!empty($user)) {
                    if(!empty($this->data->post)) {
                        $result = $this->mdl()->dbuserUpdate($this->data->post, $id);
                        if($result !== true) {
                            $this->view->errors = $result;
                            $this->view->answer = $this->mdl()->getAnswer();
                            $this->view->render('admin/forum/usereditform');
                        } else {
                            $this->redirect('/admin/forum/users/' . $page);
                        }
                    } else {
                        $this->view->user = $user;
                        $this->view->render('admin/forum/usereditform');
                    }
                } else {
                     $this->redirect('/admin/forum/users/');
                }
                break;
            case 'view':
                $user = $this->mdl()->dbgetUser($id);
                if($user) {
                    $this->view->user = $user;
                    $this->view->render('admin/forum/userinfo');
                } else {
                    $this->redirect('/admin/forum/users/' . $page);
                }
                break;
            case 'themes':
                $user = $this->mdl()->dbgetUser($id);
                if($user) {
                    $params = $this->prepareParameters(2);
                    // Проверка страницы раздела
                    if(!empty($params['page'])) {
                        $page = $this->mdl()->idCheck($params['page']);
                    } else {
                        $page = 1;
                    }
                    $num = $this->mdl('Forum')->dbauthorthemeBigger($user->id_author, $this->news_limit, 'all');

                    if($num) {
                        $max_page = ceil($num / $this->news_limit) - 1;

                        $page -= 1;
                        if($page < 1 || $page > $max_page)
                            $page = 0;
                        $begin = $page * $this->news_limit;
                        // Список страниц
                        $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/admin/forum/user/themes/' . $user->id_author . '/page');
                        // Список тем
                        $this->view->themes = $this->mdl('Forum')->dbauthorThemes($user->id_author, $begin, $this->news_limit, 'all');
                    } else {
                        $this->view->themes = $this->mdl('Forum')->dbauthorThemes($user->id_author, 0, 0, 'all');
                    }
                }
                $this->view->render('admin/forum/userthemes');
                break;
            case 'administrator':
                $this->mdl()->dbuserStatus($id, 'administrator');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'ordinary':
                $this->mdl()->dbuserStatus($id, 'ordinary');
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            case 'delete':
                $this->mdl()->dbuserDelete($id);
                $this->redirect($this->data->server['HTTP_REFERER']);
                break;
            default:
                $this->redirect('/admin/forum/users/');
                break;
        }
    }

    public function Settings() {
        $this->Forumcommon();
         if(!empty($this->data->post)) {
            $result = $this->mdl()->dbsettingsUpdate($this->data->post);
            if($result !== true) {
                $this->view->errors = $result;
                $this->view->answer = $this->mdl()->getAnswer();
                $this->view->render('admin/forum/settings');
            } else {
                $this->redirect('/admin/forum/settings/');
            }
         } else {
            $this->view->settings = $this->mdl()->dbsettingsGet();
            $this->view->render('admin/forum/settings');
         }
    }

    // Общее подключение макета страницы для форума и верхнего меню
    private function Forumcommon() {
        $this->view->forum_head = $this->view->fetchPartial('admin/forum/head');
        $this->view->setLayout('adminforumlayout');
    }
}