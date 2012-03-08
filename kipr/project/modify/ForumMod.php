<?php

class ForumMod extends MainModel
{
    
    public $answer      =  array();
    private $forum_salt  =  '5kDG1luwPjf';
    private $registered;

    // �������� ����������� ��������� �� �����
    public function idCheck($param) {
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

    // ����� ���� �������
    public function pages($max_page, $page, $link) {
        $this->libLoad('paginator');
        return $this->paginator->build($max_page, $page, $link);
    }

    public function categoriesList() {
        return $this->getCategories();
    }

    public function postCount($id, $all = 'none') {
        $cond = $all == 'none' ? 'AND hide != "hide"' : "";
        return Forumpost::count(array('conditions' => 'id_theme = '.$id.' '.$cond));
    }

    public function markReaded($id_forum) {
        return $this->setTime($this->getRegisteredUser(), true, $id_forum);
    }

    public function themesPostcount($themes, $all = 'none') {
        if(is_array($themes)) {
        $forum_lasttime = $this->getRegisteredUser() ? $this->getLasttime($this->getRegisteredUser(), $themes[0]->id_forum) : false;
        foreach($themes as $key => $theme) {
                $vars['posts'][$key] = $this->postCount($theme->id,  $all);
                if($forum_lasttime) {
                    $vars['new_posts'][$key] = $this->getNewposts($theme->id, $forum_lasttime);
                }
            }
            return $vars;
        } else {
            return false;
        }
    }

    public function usersBigger($value = 10) {
        return $this->countBigger('Forumauthor', $value);
    }

     // ��������� ������ ���
    public function getUsers($offset = 0, $num = 0) {
        if($num) {
            $authors = Forumauthor::find('all', array('order' => 'themes desc', 'offset' => $offset, 'limit' => $num));
        } else {
            $authors = Forumauthor::find('all', array('order' => 'themes desc'));
        }
        return $authors;
    }

     // ��������� ������ ���
    public function getThemes($parent_category, $offset = 0, $num = 0, $all = 'none') {
        $cond = $all == 'none' ? 'AND hide != "hide"' : "";
        $select = '`forum_themes`.*, `forum_authors`.statususer as `authorstat`, `lastauthor`.statususer as `lastauthorstat`';
        $joins = array('LEFT JOIN `forum_authors` ON(`forum_themes`.id_author = `forum_authors`.id_author)',
                'LEFT JOIN `forum_authors` `lastauthor` ON(`forum_themes`.id_last_author = `lastauthor`.id_author)');
        if($num) {
            $guest_records = Forumtheme::find('all', array('order' => 'time desc', 'offset' => $offset, 'limit' => $num, 'conditions' => 'id_forum = '.$parent_category.' '.$cond,
                'select' => $select, 'joins' => $joins));
        } else {
            $guest_records = Forumtheme::find('all', array('order' => 'time desc', 'conditions' => 'id_forum = '.$parent_category.' '.$cond,
                'select' => $select, 'joins' => $joins));
        }
        return $guest_records;
    }

    // ��������� � �������� ���� � �� ���������
    public function getTheme($category_id, $id) {
        $result = array();
        try {
            $theme = Forumtheme::find($id);
        } catch(Exception $e) {
            return $result;
        }
        if($theme->forumcategory->id == $category_id) {
            $result['theme'] = $theme;
            $result['cat'] = $theme->forumcategory;
            return $result;
        }
        return $result;
    }

    public function getPosts($id_theme, $mode = 0, $all = 'none') {
        $modeword = ($mode == 1) ? "desc" : "";
        $cond = $all == 'none' ? 'AND hide != "hide"' : "";
        $all_posts = Forumpost::find('all', array('conditions' => 'id_theme ='.$id_theme.' '.$cond,
            'order' => 'time '.$modeword));
        return $all_posts;
    }

    // �������������� � ���������� ��������� ������ � ����������� �� ��������� ������
    public function organizePosts(array $data, $mode = 0, $order_mode = 0) {
        $post_arr = array();
        $post_par = array();
        $users = array();
        if(!empty($data['posts'])) {
            foreach($data['posts'] as $key => $post) {
                $post_arr[$post->id] = $post;
                // ��������� ������ ������������� ��� ������ ��������
                $users[$post->id_author] = $post->id_author;

                $post_par[$post->parent_post][] = $post->id;
            }

            $users_in = implode(',', $users);
            $users = Forumauthor::all(array('conditions' => "id_author IN ({$users_in})"));
            $users_sort = array();
            foreach($users as $key => $value) {
               $users_sort[$value->id_author] = $value;
            }
            
            $lineforum = $mode;
            $lineforumup = $order_mode;
            // ��������, ���� �� ������� ����������� �������������
            $forum_lasttime = $this->getRegisteredUser() ? $this->getLasttime($this->getRegisteredUser(), $data['theme']->id_forum) : false;
            $current_author = $this->getRegisteredUser();
            $post_addt = array();

            $this->putpostPrepare(0, $data['theme']->id, $post_arr, $post_par, 2, $forum_lasttime, $current_author, $data['cat']->id, $lineforum, $lineforumup, $post_addt);
            return array('pst' => $post_arr, 'addt' => $post_addt, 'users' => $users_sort);
        }
        return false;
    }

    // ������� ������������ ������ ��������� ��� ��������� � MySQL
    // 1. ($id_parent) - ������������� ����� ��������
    // 2 ($id_theme)  - ������������� ����
    // 3 ($post_arr)  - ����������� ������ ��������� ������
    //                  ���� ������� - ������������� ����� (id_post)
    //                  ������ 2 ������ - ���� �������� ����� �� ������ ���� ������
    // 4 ($post_par)  - ����������� ������ ������������
    //                  id_parent � ����������� � ���� ������
    //                  ���� ������� - id_parent (id_post ��������)
    //                  ������ 2 ������ - �������������� ������, ����������� � id_parent
    // 5 ($indent)  -   ������
    // 6 ($last_time) - ����� ���������� ��������� ��� �����������
    // 					����������� ����� ���������
    // 7 ($current_author) - ��� �������� ������
    // 8 ($id_forum) - 	������� �����
    // 9 ($lineforum) - ��� ������
    // 10 ($lineforumup) - ����������� ���������� (��� ��������� ������)
    private function putpostPrepare($id_parent, $id_theme, &$post_arr, &$post_par,
              $indent, $last_time, $current_author, $id_forum, $lineforum, $lineforumup, &$post_addt) {
        if($lineforum == 1) {
            foreach($post_arr as $post) {
                $post_addt[$post->id]['indent'] = $indent;
                $post_addt[$post->id]['parent_post'] = $post_arr[$post->parent_post];
                $post_addt[$post->id]['answers'] = count($post_par[$post->id]);
                $post_addt[$post->id]['text'] = $this->postPrepare($post_arr[$post->id]->name);
                $post_addt[$post->id]['user'] = $post_arr[$post->id]->id_author;
                // ���� ����� ���������
                if(!empty($last_time) && $last_time < $post_arr[$post->id]->time->format('Y-m-d H:i:s')) {
                    $post_addt[$post->id]['new'] = true;
                }
            }
        } else {
            if(count($post_par[$id_parent])) {
                foreach($post_par[$id_parent] as $id_post_tmp) {
                    $posts = $post_arr[$id_post_tmp];
                    $count_answer = count($post_par[$id_post_tmp]);

                    $post_addt[$id_post_tmp]['indent'] = $indent;
                    $post_addt[$id_post_tmp]['answers'] = $count_answer;
                    $post_addt[$id_post_tmp]['parent_post'] = $post_arr[$id_parent];
                    $post_addt[$id_post_tmp]['text'] = $this->postPrepare($post_arr[$id_post_tmp]->name);
                    $post_addt[$id_post_tmp]['user'] = $post_arr[$id_post_tmp]->id_author;
                    // ���� ����� ���������
                    if(!empty($last_time) && $last_time < $post_arr[$id_post_tmp]->time->format('Y-m-d H:i:s')) {
                        $post_addt[$id_post_tmp]['new'] = true;
                    }
                    
                    // ���� � ������� ���������� ���������
                    $num_rows = count($post_par[$id_post_tmp]);
                    if($num_rows > 0) {
                        $shap_indent = 3;
                        if($num_rows * $shap_indent > 350)
                            $shap_indent = 2;
                        // ��������� ������
                        if($indent < 50)
                            $temp = ($shap_indent + $indent * (95) / 100);
                        else
                            $temp = (5 + $indent * (100 - $indent) / 100);

                        $this->putpostPrepare($id_post_tmp, $id_theme, $post_arr, $post_par,
                                  $temp, $last_time, $current_author, $id_forum, $lineforum, $lineforumup, $post_addt);
                    }
                }
            }
        }
    }

    // ������� ���������� ��� ��� ������������� ������
    public function themeBigger($parent_category, $value = 10, $all = 'none') {
        $cond = $all == 'none' ? 'AND hide != "hide"' : "";
        return $this->countBigger('Forumtheme', $value, 'id_forum = '.$parent_category.' '.$cond);
    }

     // ������� ���������� ��� ��� ������������� ������
    public function authorthemeBigger($author_id, $value = 10, $all = 'none') {
        $cond = $all == 'none' ? 'AND hide != "hide"' : "";
        return $this->countBigger('Forumtheme', $value, 'id_author = ' . $author_id . ' '.$cond);
    }

    // ����� ���� �������� ������ �� ������� ��������
    public function categories() {
        // �������� ���� �������� ������
        $categories = $this->getCategories();
        $result = array();
        foreach($categories as $key => $category) {
            $result[$key]['cat'] = $category;
            $forum_lasttime = $this->getRegisteredUser() ? $this->getLasttime($this->getRegisteredUser(), $category->id_forum) : false;
            // ��������� 3 ��������� ���
            $last_themes = Forumtheme::all(array('conditions' => 'id_forum = "' . $category->id . '" AND hide != "hide"', 'order' => 'time desc', 'limit' => 3));
            if(!empty($last_themes)) {
                foreach($last_themes as $theme_key => $theme) {
                    $result[$key]['last_themes'][$theme_key]['theme'] = $theme;
                    // ���������� ��������� � ����
                    $result[$key]['last_themes'][$theme_key]['posts'] = count($theme->forumpost);
                    if($forum_lasttime) {
                        $result[$key]['last_themes'][$theme_key]['new_posts'] = $this->getNewposts($theme->id_theme, $forum_lasttime);
                    }
                }
            }
        }
        return $result;
    }

     // ��������� ���������� ��������� � �������
    private function countBigger($table, $value = 10, $condition = '') {
        $count = $table::count(array('conditions' => $condition));
        return $count > $value ? $count : false;
    }

    public function getCategories($all = '') {
        $conditions = ($all == 'all') ? '' : 'hide = "show"';
        return Forumcategory::all(array('order' => 'pos asc', 'conditions' => $conditions));
    }

    public function registerCheck(array $post, $settings = '') {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'author',
                'value' => $post['author'],
                'field' => '���',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'pswrd',
                'value' => $post['pswrd'],
                'field' => '������',
                'req'   => 1
            ),
            array(
                'type'  => 'email',
                'name'  => 'email',
                'value' => $post['email'],
                'field' => '�������� ����',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'icq',
                'value' => $post['icq'],
            ),
            array(
                'type'  => 'url',
                'name'  => 'url',
                'value' => $post['url'],
            ),
            array(
                'type'  => 'string',
                'name'  => 'about',
                'value' => $post['about'],
                'field' => '� ����'
            )
        );
        if($this->validator->analyze($check)) {
            $this->answer = $this->validator->filtered;
            if(empty($this->validator->pswrd) || $this->validator->pswrd != $post['pswrd_again']) {
                $this->validator->errors['message'] = '��������� ������ �� ���������';
                return $this->validator->errors;
            }
            $res = Forumauthor::find_by_name($this->validator->author);
            if($res) {
                $this->validator->errors['message'] = '������ ������������ ��� ����������';
                return $this->validator->errors;
            }
            if(strlen($this->validator->author) > 35) {
                $this->validator->errors['message'] = '������� ������� ��� ������������';
                return $this->validator->errors;
            }
            if(!$settings->forum_photoload && !empty($_FILES['photo']['tmp_name'])) {
                $this->validator->errors['message'] = '��������� ��������� �������';
                return $this->validator->errors;
            }
            $photo = $this->photoLoad('photo', 2*20000);
            if(!$photo && !empty($_FILES['photo']['tmp_name'])) {
                $this->validator->errors['message'] = '�������� ������ ���� ��� ������ ������������ ������';
                return $this->validator->errors;
            }
            $new_user = new Forumauthor();
            $new_user->name = $this->validator->author;
            $new_user->passw = $this->passwordGenerate($this->validator->pswrd);
            $new_user->email = $this->validator->email;
            if(!empty($this->validator->url))$new_user->url = $this->validator->url;
            if(!empty($this->validator->icq))$new_user->icq = $this->validator->icq;
            if(!empty($this->validator->about))$new_user->about = $this->validator->about;
            if(!empty($photo))$new_user->photo = $photo;
            $new_user->sendmail = (bool)$post['sendmail'] ? 'yes' : 'no';
            $now = $this->nowTime();
            $new_user->time = $now;
            $new_user->last_time = $now;
            $new_user->save();
            // ��������� ������� ���������� ��������� ��� ������ ������������
            $lasttime = new Forumlasttime(array('id_author' => $new_user->id));
            $attr = $lasttime->attributes();
            array_shift($attr);
            foreach($attr as $key => $value) {
                $attr[$key] = $now;
            }
            $lasttime->set_attributes($attr);
            $lasttime->save();
            return true;
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            return $this->validator->errors;
        }
    }

    public function updateCheck(array $post, $settings = '') {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'pswrd',
                'value' => $post['pswrd'],
                'field' => '������',
            ),
            array(
                'type'  => 'email',
                'name'  => 'email',
                'value' => $post['email'],
                'field' => '�������� ����',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'icq',
                'value' => $post['icq'],
            ),
            array(
                'type'  => 'url',
                'name'  => 'url',
                'value' => $post['url'],
            ),
            array(
                'type'  => 'string',
                'name'  => 'about',
                'value' => $post['about'],
                'field' => '� ����'
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_author',
                'value' => $post['id_author'],
            )
        );
        if($this->validator->analyze($check)) {
            $this->answer = $this->validator->filtered;
            $this->answer['photo'] = $post['photo'];
            if($this->validator->pswrd != $post['pswrd_again']) {
                $this->validator->errors['message'] = '��������� ������ �� ���������';
                return $this->validator->errors;
            }
            $user = $this->getRegisteredUser();
            if($this->validator->id_author != $user->id_author) {
                $this->validator->errors['message'] = '������ ������������� ������������ ������������';
                return $this->validator->errors;
            }
            if(!$settings->forum_photoload && !empty($_FILES['photo']['tmp_name'])) {
                $this->validator->errors['message'] = '��������� ��������� �������';
                return $this->validator->errors;
            }
            $photo = $this->photoLoad('photo', 2*20000, $user->photo);
            if(!$photo && !empty($_FILES['photo']['tmp_name'])) {
                $this->validator->errors['message'] = '�������� ������ ���� ��� ������ ������������ ������';
                return $this->validator->errors;
            }
            if(!empty($this->validator->pswrd)) $user->passw = $this->passwordGenerate($this->validator->pswrd);
            $user->email = $this->validator->email;
            if(!empty($this->validator->url))$user->url = $this->validator->url;
            if(!empty($this->validator->icq))$user->icq = $this->validator->icq;
            if(!empty($this->validator->about))$user->about = $this->validator->about;
            if(!empty($photo))$user->photo = $photo;
            $user->sendmail = (bool)$post['sendmail'] ? 'yes' : 'no';
            $now = $this->nowTime();
            $user->time = $now;
            $user->last_time = $now;
            $user->save();
            return true;
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            $this->answer['photo'] = $post['photo'];
            return $this->validator->errors;
        }
    }


     // �������� ������ ��� ����� ������������
    public function loginCheck(array $post) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'login',
                'value' => $post['login'],
                'field' => '���',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'pswrd',
                'value' => $post['pswrd'],
                'field' => '������',
                'req'   => 1
            )
        );
        if($this->validator->analyze($check)) {
            $this->answer = $this->validator->filtered;
            $res = Forumauthor::find_by_name($this->validator->login);
            $password = $this->passwordGenerate($this->validator->pswrd);
            if(empty($res) || $res->passw != $password) {
                $this->validator->errors['message'] = '����������� ������� ��� ��� ������';
                return $this->validator->errors;
            }
            // ��������� ������ ������������ ����� ����� � ���������� ���������� � ��������� ������
            $this->cookieSetall($res->name, $res->passw);
            $forum = Forumcategory::first();
            if(!empty($forum)) {
                // ���������� ������� ���������� ����� ������������
                $res->time = $this->nowTime();
                $res->save();
            }
            return true;
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            return $this->validator->errors;
        }
    }

    // ������� ������ �� ������
    public function searchIt(array $post, $page = 1) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'words',
                'value' => $post['words'],
                'field' => '�������� �����',
                'req'   => 1
            ),
            array(
                'type' => 'int',
                'name' => 'category',
                'value' => $post['category'],
                'options' => array(
                    'options' => array(
                        'default' => 1,
                    )
                )
            ),
            array(
                'type' => 'int',
                'name' => 'numberthemes',
                'value' => $post['numberthemes'],
                'field' => '���������� ��������� ���',
                'req'   => 1,
                'options' => array(
                    'options' => array(
                        'default' => 15,
                    )
                )
            ),
            array(
                'type' => 'int',
                'name' => 'srchwhere',
                'value' => $post['srchwhere'],
                'field' => '������ �',
                'options' => array(
                    'options' => array(
                        'default' => 1,
                    )
                )
            ),
            array(
                'type' => 'int',
                'name' => 'id_forum',
                'value' => $post['id_forum'],
                'field' => '������ � ������',
                'options' => array(
                    'options' => array(
                        'default' => 0,
                        'min_range' => 0
                    )
                )
            ),
            array(
                'type' => 'int',
                'name' => 'logic',
                'field' => '������',
                'value' => $post['logic'],
            )
        );

        if($this->validator->analyze($check)) {
            if(empty($this->validator->id_forum)) $this->validator->id_forum = 0;
            if(empty($this->validator->logic)) $this->validator->logic = 0;
            $this->validator->filtered['logic'] = $this->validator->logic;
            $this->validator->filtered['id_forum'] = $this->validator->id_forum;
             // ����� ��������� ����� ������������ 70 ���������
            $this->validator->words = substr($this->validator->words, 0, 70);
            // ���������� ���� ������������ 6 �������
            $arr_words = explode(" ", $this->validator->words,7);
            if (count($arr_words) > 6) unset($arr_words[6]);
            $this->validator->words = implode(" ", $arr_words);

            if($this->validator->id_forum === 0) $tmp_id_forum = "";
            else $tmp_id_forum = "AND id_forum = {$this->validator->id_forum} ";

            // ��� ��������� ������� �������������
            $select = '`forum_themes`.*, `forum_authors`.statususer as `authorstat`';
            $joins = array('LEFT JOIN `forum_authors` ON(`forum_themes`.id_author = `forum_authors`.id_author)');

            // ������������ � ���������� SQL-��������
            switch($this->validator->srchwhere) {
                case 1: { // ����� � ��������� ��� � �������
                        $this->validator->words = trim($this->validator->words);
                        $temp = strtok($this->validator->words, " ");
                        if($this->validator->logic == 0)
                            $logstr = "or";
                        else
                            $logstr = "and";
                        while($temp) {
                            // �������������� ����� � �������� name � authors
                            if($is_query)
                                $tmp1 .= " $logstr MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
                            else
                                $tmp1 .= "MATCH (forum_themes.name,forum_themes.author) AGAINST ('$temp*' IN BOOLEAN MODE)";
                            $is_query = true;
                            $temp = strtok(" ");
                            $num_words++;
                        }
                        
                         // �������� ���������� ��������
                        $num = Forumtheme::count(array('conditions' => "{$tmp1} {$tmp_id_forum} AND hide != 'hide'"));
                        $maxpage = ceil($num / $this->validator->numberthemes) - 1;
                        $page -= 1;
                        if($page < 1 || $page > $maxpage) $page = 0;
                        $begin = $page * $this->validator->numberthemes;
                        // ��������� SQL-������
                        $themes = Forumtheme::all(array('conditions' => "{$tmp1} {$tmp_id_forum} AND hide != 'hide'", 'offset' => $begin, 'limit' => $this->validator->numberthemes, 'order' => 'time DESC',
                                  'select' => $select, 'joins' => $joins));
                        break;
                    }
                case 2: { // �������������� ����� �� ����������
                        $this->validator->words = trim($this->validator->words);
                        $temp = strtok($this->validator->words, " ");
                        if($this->validator->logic == 0)
                            $logstr = "or";
                        else
                            $logstr = "and";
                        while($temp) {
                            // �������������� ����� � �������� name � authors
                            if($is_query)
                                $tmp1 .= " $logstr MATCH (forum_themes.name,forum_themes.author) AGAINST ('$temp*' IN BOOLEAN MODE)";
                            else
                                $tmp1 .= "MATCH (name,author) AGAINST ('$temp*' IN BOOLEAN MODE)";
                            $is_query = true;
                            $temp = strtok(" ");
                            $num_words++;
                        }
                        // ��������� SQL-������
                        $posts = Forumpost::all(array('select' => 'id_theme', 'conditions' => "{$tmp1} AND hide != 'hide'",
                                  'group' => 'id_theme'));
                        if(count($posts) > 0) {
                            $pst_str = "";
                            foreach($posts as $key => $value) {
                                $pst_str .= $value->id_theme.',';
                            }
                            $pst_str = substr($pst_str, 0, -1);

                            // �������� ���������� ��������
                            $num = Forumtheme::count(array('conditions' => array("id_theme IN({$pst_str})")));
                            $maxpage = ceil($num / $this->validator->numberthemes) - 1;
                            $page -= 1;
                            if($page < 1 || $page > $maxpage) $page = 0;
                            $begin = $page * $this->validator->numberthemes;
                             // ��������� SQL-������
                            $themes = Forumtheme::all(array('conditions' => "id_theme IN({$pst_str})",  'offset' => $begin, 'limit' => $this->validator->numberthemes, 'order' => 'time DESC',
                                      'select' => $select, 'joins' => $joins));
                        }
                        break;
                    }
            }
            if(empty($themes)) {
                // ����� ��������� ��������� �� ������ � ����������� �� �������
                $tmp = explode(" ", $this->validator->words);
                $data['answer'] = $this->validator->filtered;
                if(count($tmp) > 1) {
                    foreach($tmp as $line) {
                        if(strlen($line) < 4) {
                            $data['error_message'] = '������ �������� �����, � ������ �������� ������ 4 - ���������� ��������� �� �� ������.';
                            return $data;
                        }
                    }
                } else {
                    if(strlen($this->validator->words) < 4) {
                        $data['error_message'] = '������ �������� �����, � ������ �������� ������ 4 - ���������� ��������� �� �� ������.';
                        return $data;
                    }
                }
                $data['error_message'] = '� ���������, �� ������ ������� ������ �� �������. ���������� �������� ������� ������.';
                return $data;
            }
            $data = array();

            foreach($themes as $key => $value) {
                $data['themes'][$key]['theme'] = $value;
                $data['themes'][$key]['posts'] = $this->postCount($value->id_theme);
            }
            if($maxpage > 0) {
                $link = "words/" . rawurlencode($this->validator->words) . "/numberthemes/{$this->validator->numberthemes}/srchwhere/{$this->validator->srchwhere}/logic/{$this->validator->logic}/category/{$this->validator->category}";
                $data['pagelist'] = $this->pages($maxpage, $page, '/forum/search/' . $link . '/page');
            }
            $data['answer'] = $this->validator->filtered;
            return $data;
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            return array('errors' => $this->validator->errors);
        }
    }

    // ��������� ������ ��� ������������
    public function passwordGenerate($pass) {
        return md5($this->forum_salt.$pass);
    }

    // �������� ���������� ������������
    private function photoLoad($name, $size, $oldphoto = "") {
        $url_photo = "";
        // ���� ���� ������ ���������� �� ������,
        // ���������� � �� ������ � ���������������
        if(!empty($_FILES[$name]['tmp_name'])) {
            // ��������� �� ������ �� ���� 512 ��
            if($_FILES[$name]['size'] > $size) {
               return false;
            } else {
                // ��������� �� ����� ����� ����������
                $ext = strrchr($_FILES[$name]['name'], ".");
                // ��������� ��������� ����� ������ ������������ �������
                $extentions = array(".jpg", ".gif", ".jpeg");
                // ��������� ���� � �����
                if(in_array($ext, $extentions)) {
                    $path = "/look/pics/photo/" . date("YmdHis", time()) . $ext;
                    // ���������� ���� �� ��������� ���������� ������� �
                    // ���������� /photo Web-����������
                    if(copy($_FILES[$name]['tmp_name'], ROOT . $path)) {
                        // ���������� ���� �� ��������� ����������
                        @unlink($_FILES[$name]['tmp_name']);
                        
                        // �������� ������ �����������
                        $info  = getimagesize(ROOT . $path);
                        $width_orig = $info[0];
                        $height_orig = $info[1];
                        list($tmp, $mime) = explode("/", $info['mime']);
                        // ������������ ������� �����������
                        $width = 32;
                        $height = 32;
                        
                        $ratio_orig = $width_orig / $height_orig;

                        if($width / $height > $ratio_orig) {
                            $width = $height * $ratio_orig;
                        } else {
                            $height = $width / $ratio_orig;
                        }

                        $image_p = imagecreatetruecolor($width, $height);
                        switch($mime) {
                            case 'jpeg':
                                $image = imagecreatefromjpeg(ROOT . $path);
                                break;
                            case 'gif':
                                $image = imagecreatefromgif(ROOT . $path);
                                break;
                        }
                        if(empty($image)) return false;
                        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                        $func = 'image'.$mime;
                        $succ = $func($image_p, ROOT . $path, 100);

                        // ����� ��������� ������ ������� ������� ������
                        if(!empty($oldphoto)) @unlink(ROOT . $oldphoto);
                        // �������� ����� ������� � �����
                        chmod(ROOT . $path, 0644);
                        $url_photo = $path;
                    }
                }
            }
        }
        return $url_photo;
    }

    // ��� ������� ���������� ���������� ������� ���������� ���������
    // ������������ � ������������� ���� ��� ����������� �������� ��
    // ��������� �����. ��� ������ ��������� �������� ������ ����������
    // ���������� ������� ���������� ��������� �������� ������, ��� ����
    // ������� ����� ������������ � ���������� ��������� - ���� ��� ������
    // 20 �����, ������ last_time ����������� ��� ������ ��������, � � ����
    // ������� ����� �����.
    // $author - ��� ������������
    // $enter - ���� true - �������������� ���������� �����
    public function setTime($author, $enter, $id_forum) {

        if(empty($id_forum)) return false;

        $user = $author;
        if(!empty($user) && !empty($id_forum)) {
            $now_time = 'now' . $id_forum;
            $lasttime = Forumlasttime::find_by_id_author($user->id);

            
            if(empty($lasttime)) {
                $lasttime = new Forumlasttime(array('id_author' => $user->id));
                $lasttime->save();
            } else {
                if(!empty($lasttime->$now_time)) {
                    $temptime = $lasttime->$now_time->getTimestamp();
                } else {
                    $temptime = 0;
                }
            }

            try {
                // ���� � ������� ���������� ��������� ������ ������ 20 �����
                if((time() - $temptime) / 60 > 20 || $enter) {
                    // ������������� ����� �����
                    $lasttime_field = 'last_time' . $id_forum;
                    $lasttime->$lasttime_field = date("Y-m-d H:i:s", $temptime);
                }

                // ������� ����������������� �����
                $now = $this->nowTime();
            } catch(Exception $e) {
                return false;
            }
            // � � ����� ������ ��������� 
            // ����� ���������� ��������� ����������
            $lasttime->$now_time = $now;
            $lasttime->save();
            $user->time = $now;
            $user->save();
        }
        return true;
    }

    function getLasttime($current_author, $id_forum) {
        if(empty($id_forum)) return false;
        // ���� ������������ ����������� - ���������
        // ���� ���������� ��������� �� �������� �������
        $lasttime = Forumlasttime::find_by_id_author($current_author->id);
        if(!empty($lasttime)) {
            $last_time = 'last_time' . $id_forum;
            $now_time = 'now' . $id_forum;

            $forum_lasttime = $lasttime->$last_time;
            $forum_nowtime = $lasttime->$now_time;
            
            if(is_object($forum_lasttime)) {
                $forum_lasttime = $forum_lasttime->format('Y-m-d H:i:s');
            }
            if(!empty($forum_nowtime)) {
                $forum_nowtime = $forum_nowtime->getTimestamp();
            }

            // ���� � ������� ���������� ��������� ������ ������ 20 �����
            if((time() - $forum_nowtime) / 60 > 20) {
                // ��������� ����� ����� �����
                $forum_lasttime = date('Y-m-d H:i:s', $forum_nowtime);
            }
        }
        return $forum_lasttime;
    }

    public function userCheck($cookie = array()) {
        if(empty($cookie['author']) || empty($cookie['wrdp'])) return false;
        
        $this->libLoad('validator');
        $check = array(
            array(
                'type' => 'string',
                'name' => 'author',
                'value' => $cookie['author']
            ),
            array(
                'type' => 'string',
                'name' => 'wrdp',
                'value' => $cookie['wrdp']
            )
        );
        if($this->validator->analyze($check)) {
            $user = Forumauthor::find_by_name($this->validator->author);
            if(!empty($user) && $user->passw == $this->validator->wrdp) {
                // ���������� ������������ ��� ���������� ��������
                $this->setRegisteredUser($user);
                return $user->id_author;
            } 
        }
        return false;
    }

    public function getMessage($id, $theme_id = 0, $caregory_id = 0) {
        $post = Forumpost::find_by_id_post($id, array('conditions' => 'hide != "hide"'));
        // ���� �� ������� ��������� � ������ ���������������
        if(empty($post)) return false;
        if($post->id_theme != $theme_id || $post->forumtheme->id_forum != $caregory_id) return false;
        $quotetext = str_replace("\\r\\n","\\n>",addcslashes(">".$post->name,"\0..\37\74\76'\\"));
        $post_text = $this->postPrepare($post->name);
        $smiles = $this->smilesPrepare(ROOT."/look/pic/smiles/", "/look/pic/smiles");
        $data = array(
            'author'     => $post->author,
            'theme'      => $theme_id,
            'category'   => $caregory_id,
            'post'       => $post_text,
            'message_id' => $id,
            'quotetext'  => $quotetext,
            'smiles'     => $smiles
        );
        return $data;
    }

    public function messageAdd(array $post, $author) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'message',
                'value' => $post['message'],
                'field' => '���������',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'theme',
                'value' => $post['theme']
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_post',
                'value' => $post['id_post']
            ),
            array(
                'type'  => 'int',
                'name'  => 'category',
                'value' => $post['category']
            )
        );
        if($this->validator->analyze($check)) {
            $theme = Forumtheme::find_by_id_theme($this->validator->theme, array('conditions' => 'hide != "hide"'));
            if(empty($theme)) {
                $this->validator->errors['message'] = '����� ���� ������ �� ����������';
                return $this->validator->errors;
            }
            if($theme->hide == 'lock') {
                $this->validator->errors['message'] = '���� �������, ��������� ��������� ������';
                return $this->validator->errors;
            }
            $post = Forumpost::find_by_id_post($this->validator->id_post, array('conditions' => 'hide != "hide"'));
            if(empty($post)) {
                $this->validator->errors['message'] = '���������, �� ������� �� ������ �������� �� ����������';
                return $this->validator->errors;
            } else {
                if($post->hide == 'lock') {
                     $this->validator->errors['message'] = '���������, �� ������� �� ���������, ������� ��� �������';
                    return $this->validator->errors;
                }
            }
            if($theme->id_theme != $post->id_theme) {
                $this->validator->errors['message'] = '������� ������ �� ������ ����';
                return $this->validator->errors;
            }
            $post_author = Forumauthor::find_by_name($author);
            $now_time = $this->nowTime();
            // ���������� ������ ���������
            $insertpost = new Forumpost();
            $insertpost->name = $this->validator->message;
            $insertpost->author = $author;
            $insertpost->id_author = $post_author->id_author;
            $insertpost->hide = 'show';
            $insertpost->time = $now_time;
            $insertpost->parent_post = $this->validator->id_post;
            $insertpost->id_theme = $this->validator->theme;
            $insertpost->save();

            // ���������� ���������� ������ ����, � ������� ����������� ���������
            $theme->last_author = $author;
            $theme->id_last_author = $post_author->id_author;
            $theme->time = $now_time;
            $theme->save();
            
            // ���������� ���������� ��������� ������� ������
            $post_author->themes += 1;
            $post_author->save();
            return true;
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            return $this->validator->errors;
        }
    }

    public function themeAdd(array $post, $author) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type'  => 'string',
                'name'  => 'messagefield',
                'value' => $post['message'],
                'field' => '���������',
                'req'   => 1
            ),
            array(
                'type'  => 'string',
                'name'  => 'sub',
                'value' => $post['sub'],
                'field' => '����',
                'req'   => 1
            ),
            array(
                'type'  => 'int',
                'name'  => 'theme',
                'value' => $post['theme']
            ),
            array(
                'type'  => 'int',
                'name'  => 'id_post',
                'value' => $post['id_post']
            ),
            array(
                'type'  => 'int',
                'name'  => 'category',
                'value' => $post['category']
            )
        );
        if($this->validator->analyze($check)) {
            $forum = Forumcategory::find_by_id_forum($this->validator->category, array('conditions' => 'hide != "hide"'));
            if(empty($forum)) {
                $this->validator->errors['message'] = '������ ������ �� ����������';
                return $this->validator->errors;
            }
            // ������ ��� ���������� ����� ����
            $post_author = Forumauthor::find_by_name($author);
            $now_time = $this->nowTime();
            
            $forum_theme = new Forumtheme();
            $forum_theme->name = $this->validator->sub;
            $forum_theme->author = $author;
            $forum_theme->id_author = $post_author->id_author;
            $forum_theme->last_author = $author;
            $forum_theme->id_last_author = $post_author->id_author;
            $forum_theme->hide = 'show';
            $forum_theme->time = $now_time;
            $forum_theme->id_forum = $this->validator->category;
            $forum_theme->save();

            // ���������� ��������� � ��������� ����
            $insertpost = new Forumpost();
            $insertpost->name = $this->validator->messagefield;
            $insertpost->author = $author;
            $insertpost->id_author = $post_author->id_author;
            $insertpost->hide = 'show';
            $insertpost->time = $now_time;
            $insertpost->parent_post = 0;
            $insertpost->id_theme = $forum_theme->id_theme;
            $insertpost->save();

            // ���������� ���������� ��������� ������� ������
            $post_author->themes += 1;
            $post_author->save();
            return array('category' => $this->validator->category, 'theme' => $forum_theme->id_theme);
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            return $this->validator->errors;
        }
    }

    // ��������� ���� ���, �� ������� ������� ������������
    public function authorAnswers($user_id) {
        $themes_post = Forumpost::all(array('conditions' => array('id_author' => $user_id,  'hide' => 'show'),
            'group' => 'id_theme', 'limit' => '30', 'order' => 'time DESC'));
        $themes = array();

        $select = '`forum_themes`.*, `forum_authors`.statususer as `authorstat`';
        $joins = array('LEFT JOIN `forum_authors` ON(`forum_themes`.id_author = `forum_authors`.id_author)');
        
        // ����� ���� ��� � ���������� ��������� � ���
        foreach($themes_post as $key => $post) {
            $themes[$key]['theme'] = Forumtheme::find(array('conditions' => 'forum_themes.id_theme = '.$post->id_theme, 'select' => $select,
                'joins' => $joins));
            $themes[$key]['posts'] = count($themes[$key]['theme']->forumpost);
        }
        return $themes;
    }

     // ��������� ���� ���, �� ������� ������ ������������
    public function authorThemes($user_id,  $offset = 0, $num = 0, $all = 'none') {
        $cond = $all == 'none' ? 'AND hide != "hide"' : "";

        // ��� ��������� ������� �������������
        $select = '`forum_themes`.*, `forum_authors`.statususer as `authorstat`';
        $joins = array('LEFT JOIN `forum_authors` ON(`forum_themes`.id_author = `forum_authors`.id_author)');

        if($num) {
            $auth_themes = Forumtheme::all(array('conditions' => 'forum_themes.id_author =' . $user_id . ' '.$cond,
                'order' => 'time DESC', 'offset' => $offset, 'limit' => $num, 'select' => $select, 'joins' => $joins));
        } else {
            $auth_themes = Forumtheme::all(array('conditions' => 'forum_themes.id_author =' . $user_id . ' '.$cond,
                'order' => 'time DESC', 'select' => $select, 'joins' => $joins));
        }
        $themes = array();
        // ����� ���� ��� � ���������� ��������� � ���
        foreach($auth_themes as $key => $thm) {
            $themes[$key]['theme'] = $thm;
            $themes[$key]['posts'] = Forumpost::count(array('conditions' => 'id_theme = '.$thm->id_theme));
        }
        return $themes;
    }

    public function getInfo($user_id = 0) {
        try {
            $author = Forumauthor::find($user_id);
        } catch (Exception $e) {
            return false;
        }
        $data['author'] = $author;
        $data['about'] = nl2br($author->about);
        return $data;
    }

    public function cookieDestroy() {
        setcookie("author", "", 0, "/");
        setcookie("wrdp", "", 0, "/");
    }

    public function getSmiles() {
        return $this->smilesPrepare(ROOT."/look/pic/smiles/", "/look/pic/smiles");
    }

    // ����� ��������� � ����
    function getNewposts($id_theme, $lasttime = 0) {
        // ������������ ���������� ����� ��������� � ������� ����
        $count = Forumpost::count(array('conditions' => 'id_theme = '.$id_theme.' AND "'.$lasttime.'" < `time` AND hide != "hide"'));
        return $count;
    }

    private function nowTime() {
        $now = new DateTime();
        return $now->format('Y-m-d H:i:s');
    }

    private function cookieSetall($author, $wrdp) {
        $settings = $this->settingsGet();
        $days = $settings->forum_cooktime;
        setcookie("author", $author, time() + 3600 * 24 * $days, "/");
        setcookie("wrdp", $wrdp, time() + 3600 * 24 * $days, "/");
    }

    private function smilesPrepare($dirname, $link) {
        $dir = opendir($dirname);
        if(!$dir) return false;
        $smiles = "";
        while($smile = readdir($dir)) {
            if(($smile != ".")
                      && ($smile != "..")
                      && ($smile != "Thumbs.db")
                      && (substr($smile, -3) != "php")
                      && (is_dir($smile) != "true" )) {
                $smiles .= " <a href=# onClick=\"javascript:tag(' [:" . substr($smile, 0, strpos($smile, ".")) . ":] ',''); return false;\"><img src='{$link}/$smile' border=0 hspace=1/></a>";
            }
        }
        return $smiles;
    }

    // ��������� ��������� ����� �������
    private function postPrepare($text) {
        $text = preg_replace_callback(
                            "|([a-z�-�\d!]{35,})|i",
                            function($matches) {
                                return wordwrap($matches[1], 35, ' ', 1);
                            },
                            $text);
        $text = nl2br(htmlspecialchars($text));
        // ��������
        $dirname = ROOT.'/look/pic/smiles/';
        if(file_exists($dirname)) {
            $text = str_replace("[:", "<img align=middle src='/look/pic/smiles/", $text);
            $text = str_replace(":]", ".gif' />", $text);
        }
        // ����
        $text = preg_replace("#\[b\](.+)\[\/b\]#isU", '<b>\\1</b>', $text);
        $text = preg_replace("#\[i\](.+)\[\/i\]#isU", '<i>\\1</i>', $text);
        $text = preg_replace("#\[url\][\s]*((?=http:)[\S]*)[\s]*\[\/url\]#si", '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = preg_replace("#\[url\][\s]*((?=https:)[\S]*)[\s]*\[\/url\]#si", '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = preg_replace("#\[url\][\s]*((?=ftp:)[\S]*)[\s]*\[\/url\]#si", '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = preg_replace("#\[url[\s]*=[\s]*((?=http:)[\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU",
                            '<a href="\\1" target=_blank>\\2</a>',
                            $text);
        return $text;
    }

    // �������� ������������
    public function getRegisteredUser() {
        return $this->registered;
    }

    public function setRegisteredUser(Forumauthor $value) {
        $this->registered = $value;
    }

     // ��������� �������� ��� �����
    public function settingsGet() {
        $settings = Settings::first();
        if(empty($settings)) $settings = new Settings();
        return $settings;
    }
}