<?php

class AbiturientMod extends MainModel
{
     public function getLecturers() {
        return Page::find('all', array('conditions' => 'type = "prepodavateli"', 'order' => 'header asc'));
    }

     public function getLecturer($name) {
        $this->libLoad('validator');
        $check  = array(
            array(
                'type' => 'string',
                'name' => 'name',
                'value'=> $name
            )
        );
        $this->validator->analyze($check);
        return Page::first(array('linkname' => $this->validator->name));
    }

    public function getPage($name) {
        return Page::find_by_type($name);
    }

    private function folderInside($folder) {
        $this->ormLoad();
        return Document::all(array('order' => 'name asc', 'conditions' => 'parent = "'.$folder.'" and folder = 0'));
    }

    // Получение списка документов для вывода на главной странице
    public function documentsGet($folder) {
        $res = $this->folderInside($folder);
        $array = array();
        $extensions = array('zip', 'rar', 'doc', 'docx', 'ppt', 'xls', 'pptx', 'rtf');
        foreach($res as $key => $item) {
            $ext = end(explode('.', $item->filename));
            if(in_array($ext, $extensions)) $ext = substr($ext, 0, 3);
            else $ext = '';
            $size = $this->fileSizeInfo($item->size);
            $array[$key] = array(
                'name' => $item->name,
                'link' => $item->link . '/' . $item->filename,
                'size' => '('.$size[0].' '.$size[1].')',
                'ext'  => $ext,
                'added' => $item->addtime->format('d.m.Y'),
                'comment' => $item->comment
            );
        }
        return $array;
    }

    public function folderCheck($name) {
        $this->libLoad('validator');
        $check = array(
          array(
              'name' => 'folder',
              'type' => 'string',
              'value' => $name
          )
        );
        $this->validator->analyze($check);
        return $this->validator->folder;
    }

    // Преобразование размера файла
    private function fileSizeInfo($fs) {
        $bytes = array('KB', 'KB', 'MB', 'GB', 'TB');
        // Значения, которые меньше 1 Кб
        if($fs <= 999) {
            $fs = 1;
        }
        for($i = 0; $fs > 999; $i++) {
            $fs /= 1024;
        }
        return array(ceil($fs), $bytes[$i]);
    }

    public function folderInfo($folder) {
        return Document::find_by_filename($folder);
    }

    public function prevSectionsGet($folder, $filename, $parent) {
        $doc = $this->folderInfo($folder);
        if($doc) {
            return Document::all(array('order' => 'name asc', 'conditions' => '(parent = "'.$parent.'" OR filename = "'.$parent.'") and folder = 1 AND parent != "docs" AND filename != "'.$filename.'"'));
        }
        return false;
    }

    public function currentSectionsGet($folder) {
        return Document::all(array('order' => 'name asc', 'conditions' => 'parent = "'.$folder.'" and folder = 1'));
    }
}