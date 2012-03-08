<?php

class MainMod extends MainModel
{
    private $confirmsalt = 'Nep7UcrAcr';
    private $stopsalt    = 'rAqacra4';
    private $frombox     = 'admin@kipr.susu.ac.ru';
    public  $answer      = '';

// ��������� ��������
    public function newsGet() {
        $set = Settings::first();
        $news = News::find('all', array('limit' => $set->maxnews, 'order' => 'pubtime desc'));
        // ��� ������ ���������� ������������
        foreach($news as $pos => $new) {
            $news[$pos]->text = count($new->newscomment);
        }
        return $news;
    }

// ����� ����� �������
    public function newsShow($id) {
        $news_record = News::find_by_id($id);
        if(!$news_record) return false;
        return $news_record;
    }

// �������� ����������� ��������� �� �������
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

// ��������� ������� ��������
    public function newsRecords($offset = 0, $num = 0) {
        if($num) {
            $news = News::find('all', array('order' => 'pubtime desc', 'offset' => $offset, 'limit' => $num));
        } else {
            $news = News::find('all', array('order' => 'pubtime desc'));
        }
        foreach($news as $pos => $new) {
            $news[$pos]->text = count($new->newscomment);
        }
        return $news;
    }

// ��������� ���������� ��������� � �������
    public function countBigger($table, $value = 10) {
        $count = $table::count();
        return $count > $value ? $count : false;
    }

// ����� ���� �������
    public function pages($max_page, $page, $link) {
        $this->libLoad('paginator');
        return $this->paginator->build($max_page, $page, $link);
    }

    // ��������� ������ ���������� ��� ������ �� ������� ��������
    public function documentsGet($foldername) {
        $settings = $this->settingsGet();
        $res = $this->documentsList($settings->maxdocs, $foldername);
        $array = array();
        $extensions = array('zip', 'rar', 'doc', 'docx', 'ppt', 'xls', 'pptx', 'rtf');
		if(!empty($res)) {
			foreach($res as $key => $item) {
				$ext = end(explode('.', $item->filename));
				if(in_array($ext, $extensions)) $ext = substr($ext, 0, 3);
				else $ext = '';
				$size = $this->fileSizeInfo($item->size);
				$array[$key] = array(
					'name' => $item->name,
					'link' => $item->link . '/' . $item->filename,
					'size' => '('.$size[0].' '.$size[1].')',
					'ext'  => $ext
				);
			}
		}
        return $array;
    }

// ��������� �������� ��� �����
    public function settingsGet() {
        return Settings::first();
    }

// ����� ������ ����������
    public function documentsList($num = 0, $foldername = '') {
        if($num) {
            $documents = Document::find('all', array('order' => 'name asc', 'limit' => $num, 'conditions' => "folder = 0 AND link LIKE '%{$foldername}%' AND important = 1"));
        }
        return $documents;
    }

    // �������������� ������� �����
    private function fileSizeInfo($fs) {
        $bytes = array('KB', 'KB', 'MB', 'GB', 'TB');
        // ��������, ������� ������ 1 ��
        if($fs <= 999) {
            $fs = 1;
        }
        for($i = 0; $fs > 999; $i++) {
            $fs /= 1024;
        }
        return array(ceil($fs), $bytes[$i]);
    }

    // ����� �� ���� �����
    public function ajaxAnswer($string) {
        $string = iconv('windows-1251', 'utf-8', $string);
        echo $string;
    }


    // ���������� ��������� ����� � ���� ��� ��������
    public function mailAdd($mailbox, $ip) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type' => 'email',
                'name' => 'email',
                'value' => $mailbox
            ),
            array(
                'type' => 'ip',
                'name' => 'ip',
                'value' => $ip
            )
        );
        if($this->validator->analyze($check)) {
            $this->ormLoad();
            if(Subscription::find_by_email($this->validator->email)) {
                return $this->ajaxAnswer('������ �������� ���� ��� ��������� � ������ ��������');
            }
            // ���������� ������ ��������� ����� ��� �������� � ��������������� �������� ������ ��� �������������
            $confirmkey = $this->code($this->validator->email, 'confirm');
            $stopkey    = $this->code($this->validator->email, 'stop');
            $box = new Subscription(array('email' => $this->validator->email, 'confirmkey' => $confirmkey,
                                          'stopkey' => $stopkey, 'userip' => $this->validator->ip, 'enabled' => 0));
            $box->save();
            $header = '������������� �������� �� ������� kipr.susu.ac.ru';
            $text   = "��� �������� ����� ��� ������ � �������� �� ������� ������� ���� �����.\n";
            $text  .= "���� �� �� ������������� �� ��������, ���� ������ ���� ���������� ��������, ������� �� ��������.\n\n";
            $text  .= "��� ������������� �������� �������� �� ������:\n". Cfg::inst()->get('hostname')."mailboxconfirm/{$confirmkey}/\n\n";
            $text  .= "===========================================================================================================\n";
            $text  .= "C ���������� �����������, ������������� ".Cfg::inst()->get('hostname');
            $this->sendmail($this->validator->email, $header, $text);
            $this->ajaxAnswer('�� ��� �������� ���� ���� ������� ������ ��� �������������');
        } else {
            return $this->ajaxAnswer('����������� ������� �������� ��������� �����');
        }
    }

    public function mailKeyConfirm($key) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type' => 'string',
                'name' => 'confirm',
                'value' => $key
            )
         );
        $this->validator->analyze($check);
        $mailbox = Subscription::find_by_confirmkey($this->validator->confirm);
        if($mailbox) {
            $mailbox->confirmkey = $this->code($this->validator->confirm, 'confirm');
            $mailbox->enabled = 1;
            $mailbox->save();
            return true;
        } else {
            return false;
        }
    }

    // ������ �������� �� �������
    public function mailKeyUnsubscribe($key) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type' => 'string',
                'name' => 'stop',
                'value' => $key
            )
         );
        $this->validator->analyze($check);
        $mailbox = Subscription::find_by_stopkey($this->validator->stop);
        if($mailbox) {
            $mailbox->delete();
            return true;
        } else {
            return false;
        }
    }

    // �������� �����
    private function sendMail($address, $subject, $text) {
        $nameFrom  = 'Admin';
        $emailFrom = $this->frombox;
        $subject   = "=?windows-1251?b?" . base64_encode($subject) . "?=";
        $headers   = 'Content-Type: text/plain; charset="windows-1251"' . "\r\n" .
                     'Content-Transfer-Encoding: 8bit' . "\r\n" .
                     'From: =?windows-1251?b?' . base64_encode($nameFrom) . '?= <' . $emailFrom . '>';
        $res = mail($address, $subject, $text, $headers, "-f$emailFrom");
    }

    // ����������� ������ �������������
    private function code($key, $type) {
        $fullkey = $key;
        if($type == 'confirm') {
            $fullkey .= $this->confirmsalt;
        } elseif($type == 'stop') {
            $fullkey .= $this->stopsalt;
        }
        return md5($fullkey);
    }

    public function getPage($name) {
        return Page::find_by_type($name);
    }

    // �������� ��������� ������ �� ��������� �����
    public function answerCheck($value) {
        $this->libLoad('captcha');
        return $this->captcha->sessionCheck($value);
    }

    // ���������� ����������� � ������� � ��������� ������� ������
    public function commentInsert(array $data) {
        $this->libLoad('validator');
        $check = array(
            array(
                'type' => 'string',
                'name' => 'name',
                'value' => $data['name'],
                'field' => '���',
                'req'   => 1
            ),
            array(
                'type' => 'string',
                'name' => 'text',
                'value' => $data['text'],
                'field' => '����� ���������',
                'req'   => 1
            ),
            array(
                'type' => 'int',
                'name' => 'news_id',
                'value' => $data['news'],
                'options' => array(
                    'options' => array(
                        'default' => 1,
                        'min_range' => 1
                    )
                )
            ),
        );
        if(!empty($data['email'])) {
        $check[] =  array(
                'type' => 'email',
                'name' => 'email',
                'value' => $data['email'],
                'field' => '�������� ����'
            );
        } else {
            $this->validator->email = "";
        }
        if($this->validator->analyze($check)) {
            if(!$this->answerCheck($data['captcha'])) {
                 // ����������� ���������, ���� � ���������� ������ ���� ������
                $this->validator->errors['message'] = '����������� ������ ����������� �����';
                $this->answer = $this->validator->filtered;
                return $this->validator->errors;
            }
            $this->validator->text = nl2br($this->validator->text, true);
            $news_com = new Newscomment(array('name' => substr($this->validator->name,0, 32), 'email' => $this->validator->email,
                'text' => $this->validator->text, 'news_id' => $this->validator->news_id));
            $news_com->save();
            return true;
        } else {
            // ����������� ���������, ���� � ���������� ������ ���� ������
            $this->answer = $this->validator->filtered;
            return $this->validator->errors;
        }
    }
}