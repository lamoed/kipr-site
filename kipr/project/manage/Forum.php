<?php

/**
 * Контроллер форума, в котором находятся все функции управления форумом
 */
class Forum extends MainController
{
    private $maxonpage = 25; // Общий параметр для постраничного показа
    private $settings;
    private $registered = false;

    // Проверка авторизации пользователя и время последнего входа
    public function Head() {
        $this->registered = $this->mdl()->dbuserCheck($this->data->cookie);
        $this->settings = $this->mdl()->dbsettingsGet();
        $this->view->setTitle($this->settings->forum_name);
        if($this->registered) {
            if(strtolower($this->getCalledMethod()) == 'category') {
                $id_forum = array_values($this->prepareParameters(-1));
                $id_forum = $id_forum[0];
            } else {
                $params = $this->prepareParameters(1);
                $id_forum = $params['category'];
            }
            $id_forum = $this->mdl()->idCheck($id_forum);
            // Обновление времени посещения для форума
            $this->mdl()->setTime($this->mdl()->getRegisteredUser(), false, $id_forum);
            $this->view->username = $this->data->cookie['author'];
        }
        $this->view->setLayout('forumlayout');
        $this->view->registered = $this->registered;
    }

    // Вывод всех разделов форума с последними тремя темами в них
    public function First() {
        // Переключение раздела
        if(!empty($this->data->get['id_forum'])) {
            $id = $this->mdl()->idCheck($this->data->get['id_forum']);
            $this->redirect('/forum/category/' . $id . '/');
        }
        $default = $this->mdl()->dbcategories();
        if(!empty($default)) {
            $default['cats'] = $default;
            $default['all_cat'] = count($default['cats']);
            $this->view->useractions = $this->view->fetchPartial('forum/useractions', $default);
            $this->view->render('forum/index', $default);
        } else {
            $this->view->forum_message = 'На форуме отсутствуют или отключены все разделы';
            $this->view->render('forum/message');
        }
    }

    // Форма добавления сообщения на форум
    public function Addmessage($message_id = 0) {
        if(!$this->registered) $this->redirect('/forum/');
        $params = $this->prepareParameters(1);
        $message_id = $this->mdl()->idCheck($message_id);
        $data = $this->mdl()->dbgetMessage($message_id, $params['theme'], $params['category']);
        if(empty($data)) $this->redirect('/forum/');
        $this->view->render('forum/messageform', $data);
    }

     // Добавление новой темы на форум
    public function Addtheme($id_forum = null) {
        if(!$this->registered) $this->redirect('/forum/');
        $id_forum = $this->mdl()->idCheck($id_forum);
        $this->view->id_forum = $id_forum;
        $this->view->smiles = $this->mdl()->getSmiles();
        // Если переданы данные для добавления темы
        if(!empty($this->data->post)) {
            $complete = $this->mdl()->dbthemeAdd($this->data->post, $this->data->cookie['author']);
            if(!empty($complete['category']) && !empty($complete['theme'])) {
                $link = '/forum/theme/' . $complete['theme'] . '/category/' . $complete['category'] . '/';
                $this->redirect($link);
            } else {
                // Вывод ошибок
                $this->view->errors = $complete;
                $this->view->answer = $this->mdl()->answer;
                $this->view->id_forum = $this->data->post['category'];
            }
        }
        $this->view->render('forum/themeform');
    }

     // Вывод последних 30 тем, в которых писал пользователь
    public function Authorlastthemes($author_id = 0) {
        $author_id = $this->mdl()->idCheck($author_id);
        if(!$this->registered || $author_id == 0 || $author_id != $this->registered) $this->redirect('/forum/');
        $params = $this->prepareParameters(1);
        $this->view->themes = $this->mdl()->dbauthorAnswers($this->registered);
        $this->view->category = $this->mdl()->idCheck($params['category']);
        $this->view->render('forum/authorlist');
    }

     public function Authorlist($id = 0) {
        $this->view->render('forum/authorlist');
    }

    // Показ тем, созданных пользователем
    public function Authorthemes($author_id = 0) {
        $author_id = $this->mdl()->idCheck($author_id);
        if(!$this->registered || $author_id == 0) $this->redirect('/forum/');
        $params = $this->prepareParameters(1);
        // Проверка страницы раздела
        if(!empty($params['page'])) {
            $page = $this->mdl()->idCheck($params['page']);
        } else {
            $page = 1;
        }
        $num = $this->mdl()->dbauthorthemeBigger($author_id, $this->maxonpage);

        if($num) {
            $max_page = ceil($num / $this->maxonpage) - 1;

            $page -= 1;
            if($page < 1 || $page > $max_page)
                $page = 0;
            $begin = $page * $this->maxonpage;
            // Список страниц
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/forum/authorthemes/' . $author_id . '/category/' . $params['category'] . '/page');
            // Список тем
            $this->view->themes = $this->mdl()->dbauthorThemes($author_id, $begin, $this->maxonpage);
        } else {
            $this->view->themes = $this->mdl()->dbauthorThemes($author_id);
        }
        $this->view->category = $this->mdl()->idCheck($params['category']);
        $this->view->render('forum/authorthemes');
    }

    // Вывод списка тем раздела форума
    public function Category($id = 1) {
        $params = $this->prepareParameters(1);
        $id = $this->mdl()->idCheck($id);
        $settings = $this->settings;
        // Если форум скрыт, а на него пытаются зайти, то делается перенаправление
        $category = $this->mdl('Main', array('folder' => '/admin'))->getCategory($id);
        $this->view->setTitle($this->view->getTitle().' / '.$category->name);
        if($category && $category->hide == 'hide') $this->redirect('/forum/');

        // Проверка страницы раздела
        if(!empty($params['page'])) {
            $page = $this->mdl()->idCheck($params['page']);
        } else {
            $page = 1;
        }
        $num = $this->mdl()->dbthemeBigger($id, $settings->forum_themes);

        if($num) {
            $max_page = ceil($num / $settings->forum_themes) - 1;

            $page -= 1;
            if($page < 1 || $page > $max_page)
                $page = 0;
            $begin = $page * $settings->forum_themes;
            // Список страниц
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/forum/category/' . $id . '/page');
            // Список тем
            $themes = $this->view->themes = $this->mdl()->getThemes($id, $begin, $settings->forum_themes);
        } else {
            $themes = $this->view->themes = $this->mdl()->getThemes($id);
        }
        $vars = array();
        if($themes) {
            $vars = $this->mdl()->themesPostcount($themes);
        }
        $vars['cats'] = $this->mdl()->categoriesList();
        if(empty($vars)) {
            $this->redirect('/forum/');
        } else {
            // Количество всех разделов
            $vars['all_cat'] = count($vars['cats']);
        }
        $this->view->current_forum = $id;
        $this->view->topmenu = $this->view->fetchPartial('forum/topmenu', $vars);
        $this->view->useractions = $this->view->fetchPartial('forum/useractions', $vars);
        $this->view->render('forum/category', $vars);
    }

    // Форма входа для пользователей
    public function Login() {
        if($this->registered) $this->redirect('/forum/');
        if(!empty($this->data->post)) {
            $result = $this->mdl()->dbloginCheck($this->data->post);
            if($result === true) {
                $this->redirect('/forum/');
            } else {
                $this->view->errors = $result;
                $this->view->answer = $this->mdl()->answer;
                $this->view->render('forum/login');
            }
        }
        $this->view->render('forum/login');
    }

    // Выход пользователя и уничтожение куков
    public function Logout() {
        $this->mdl()->cookieDestroy();
        $this->redirect('/forum/');
    }

    public function Markreaded($id_forum = 0) {
        if(!$this->registered || $id_forum == 0) $this->redirect('/forum/');
        $id_forum = $this->mdl()->idCheck($id_forum);
        $this->mdl()->dbmarkReaded($id_forum);
        $this->redirect("/forum/category/{$id_forum}");
    }

     // Запись сообщения на форум
    public function Postwrite() {
        if(!$this->registered || empty($this->data->post)) $this->redirect('/forum/');
        if(!empty($this->data->post)) {
            $complete = $this->mdl()->dbmessageAdd($this->data->post, $this->data->cookie['author']);
            if($complete === true) {
                $link = '/forum/theme/' . $this->data->post['theme'] . '/category/' . $this->data->post['category'] . '/';
                $this->redirect($link);
            } else {
                $this->view->errors = $complete;
                $this->view->answer = $this->mdl()->answer;
                $message_id = $this->data->post['id_post'];
                $data = array();
                $data = $this->mdl()->dbgetMessage($message_id, $this->data->post['theme'], $this->data->post['category']);
                $this->view->render('forum/messageform', $data);
            }
        }
    }

    // Вывод формы регистрации
    public function Register() {
        if($this->registered) $this->redirect('/forum/');
        $settings = $this->view->settings = $this->settings;
        if(!empty($this->data->post)) {
            $result = $this->mdl()->dbregisterCheck($this->data->post, $settings);
            if($result === true) {
                $this->view->message = 'Регистрация прошла успешно, теперь вы можете
                    зайти под своим аккаунтом';
                $this->forward($this, 'First');
            } else {
                $this->view->errors = $result;
                $this->view->answer = $this->mdl()->answer;
                $this->view->render('forum/register');
            }
        }
        $this->view->render('forum/register');
    }

    public function Search() {
        if(!$this->registered) $this->redirect('/forum/');
        $params = $this->prepareParameters(0);
        if(!empty($this->data->post)) {
            $result = $this->mdl()->dbsearchIt($this->data->post);
        } else {
            if(count($params) > 2) {
                $params['words'] = urldecode($params['words']);
                $result = $this->mdl()->dbsearchIt($params, $this->mdl()->idCheck($params['page']));
            }
        }
        if(!empty($result['errors'])) {
            $this->view->errors = $result['errors'];
            $this->view->answer = $this->mdl()->answer;
        } else {
            $data = $result;
        }
        $data['cats'] = $this->mdl()->getCategories();
        $data['category'] = $this->mdl()->idCheck($params['category']);
        $this->view->render('forum/search', $data);
    }

    // Показ отдельной темы форума
    public function Theme($id = 1) {
        $params = $this->prepareParameters(1);
        $id = $this->mdl()->idCheck($id);
        if(isset($params['linear'])) {
            if($params['linear'] == 1) {
                setcookie('lineforum', 1, 0, '/');
                if($params['up'] == 1) {
                    setcookie('lineforumup', 1, 0, '/');
                } else {
                    setcookie('lineforumup', '', 0, '/');
                }
                $this->redirect('/forum/theme/' . $id . '/category/' . $params['category'] . '/');
            } else {
                setcookie('lineforum', '', 0, '/');
                setcookie('lineforumup', '', 0, '/');
                $this->redirect('/forum/theme/' . $id . '/category/' . $params['category'] . '/');
            }
        }

        $data = array();

        // Режим вывода сообщений
        if(!empty($this->data->cookie['lineforum'])) {
            $lineforum = 1;
            if(!empty($this->data->cookie['lineforumup']) &&
                      $this->data->cookie['lineforum'] == 1) {
                // Вывод темы с последними сообщениями вверху
                $lineforumup = 1;
            }
        } else {
            $lineforum = 0;
            // Режим упорядочивания при линейном выводе
            $lineforumup = 0;
        }

        if(!empty($params['category'])) {
            $params['category'] = $this->mdl()->idCheck($params['category']);
        } else {
            $params['category'] = 1;
        }
        $data = $this->mdl()->dbgetTheme($params['category'], $id);
        if(empty($data)) {
            $this->redirect('/forum/');
        }
        // Заголовок при выводе темы
        $this->view->setTitle($this->view->getTitle().' / '.$data['cat']->name.' / '.$data['theme']->name);
        $data['posts'] = $this->mdl()->getPosts($data['theme']->id, $lineforumup);
        $data['cats'] = $this->mdl()->categoriesList();
        $data['all_cat'] = count($data['cats']);
        $data['posts'] = $this->mdl()->organizePosts($data, $lineforum, $lineforumup);
        $this->view->lineforum = $lineforum;
        $this->view->lineforumup = $lineforumup;
        $this->view->current_forum = $data['cat']->id;
        $this->view->topmenu = $this->view->fetchPartial('forum/topmenu', $data);
        $this->view->useractions = $this->view->fetchPartial('forum/useractions', $data);
        if($lineforum == 1) {
            $this->view->render('forum/themelinear', $data);
        } else {
            $this->view->render('forum/themestruct', $data);
        }
    }

    // Обновление личных данных пользователя
    public function Update($id_author = 0) {
        if(!$this->registered) $this->redirect('/forum/');
        $params = $this->prepareParameters(1);
        $user = $this->mdl()->getRegisteredUser();
        $settings = $this->view->settings = $this->settings;
        if(!empty($this->data->post)) {
            $this->data->post['photo'] = empty($this->data->post['photo']) ? $user->photo : "";
            $result = $this->mdl()->dbupdateCheck($this->data->post, $settings);
            if($result === true) {
                $this->view->message = 'Ваши личные данные были обновлены';
                $this->forward($this, 'First');
            } else {
                $this->view->errors = $result;
                $answer = $this->mdl()->answer;
                $answer['author'] = $user->name;
                $answer['photo'] = $user->photo;
                $this->view->answer = $answer;
                $this->view->render('forum/update');
            }
        } else {
            if($id_author != $this->registered) $this->redirect('/forum/');
            $answer = array(
                'author' => $user->name, 'email' => $user->email,
                'icq' => $user->icq, 'url' => $user->url,
                'about' => $user->about, 'photo' => $user->photo,
                'id_author' => $id_author, 'category' => $params['category']
            );
            $this->view->answer = $answer;
            $this->view->render('forum/update');
        }
    }

    // Вывод информации о пользователе
    public function Userinfo($user_id = 0) {
        if(!$this->registered) {
            $this->view->message = "Доступ незарегистрированным пользователям запрещен";
            $this->view->render('forum/userinfo');
        }
        $user_id = $this->mdl()->idCheck($user_id);
        $data = $this->mdl()->dbgetInfo($user_id);
        $params = $this->prepareParameters(1);
        $this->view->category = $this->mdl()->idCheck($params['category']);
        if(empty($data)) {
            $this->view->message = "Такого пользователя не существует";
            $this->view->render('forum/userinfo');
        }
        // Заголовок для страницы
        $this->view->setTitle($this->view->getTitle() . ' - ' . $data['author']->name);
        $this->view->render('forum/userinfo', $data);
    }

    public function Users($id_forum = 0) {
        if(!$this->registered) $this->redirect('/forum/');
        $id_forum = $this->mdl()->idCheck($id_forum);
        if($id_forum == 0) $id_forum = 1;
        $params = $this->prepareParameters(1);
        if(!empty($params['page'])) {
            $page = $this->mdl()->idCheck($params['page']);
        } else {
            $page = 1;
        }
        $num = $this->mdl()->dbusersBigger($this->maxonpage);

        if($num) {
            $max_page = ceil($num / $this->maxonpage) - 1;

            $page -= 1;
            if($page < 1 || $page > $max_page)
                $page = 0;
            $begin = $page * $this->maxonpage;
            // Список страниц
            $this->view->pagelist = $this->mdl()->pages($max_page, $page, '/forum/users/' . $id_forum . '/page');
            // Список тем
            $this->view->users =  $this->mdl()->dbgetUsers($begin, $this->maxonpage);
        } else {
            $this->view->users = $this->mdl()->dbgetUsers();
        }
        $this->view->category = $id_forum;
        $this->view->render('forum/users');
    }

    public function Test() {
        var_dump($this->mdl()->dbtryJoin());
    }
    
}