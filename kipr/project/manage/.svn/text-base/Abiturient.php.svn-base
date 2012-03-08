<?php

class Abiturient extends MainController
{

    public function Head() {
        $this->view->setTitle($this->getTitle());
    }
    
    public function First() {
        $this->forward($this, 'information');
    }

    public function lecturers($name = '') {
        if(empty($name)) {
            $this->view->lecturerlist = $this->mdl()->dbgetLecturers();
            $this->view->render('main/lecturerstable');
        } else {
            $res = $this->mdl()->dbgetLecturer($name);
            if(!$res) $this->redirect('/abiturient/lecturers');
            else {
                $this->view->setTitle($this->view->getTitle().' - '.$res->header);
                $this->view->lecturer = $res;
                $this->view->render('main/lecturerpage');
            }
        }
    }

    public function speciality() {
        $this->showPage('speciality');
    }

    public function information() {
       $this->showPage('abiturient');
    }

    private function showPage($name) {
        $res = $this->mdl()->dbgetPage($name);
        if(empty($res)) $this->redirect('/');
        $this->view->setTitle($res->header);
        $this->view->page = $res;
        $this->view->render('main/page');
    }

    public function documents($folder = 'abiturient') {
        $fld = $this->mdl()->folderCheck($folder);
        $this->view->docs     = $this->mdl()->documentsGet($fld);
        $this->view->sections = $this->mdl()->currentSectionsGet($fld);
        $current_fld = $this->mdl()->folderInfo($fld);
        if($current_fld) {
            $this->view->setTitle($this->view->getTitle() . ' - ' . $current_fld->name);
            $this->view->docs_name = $current_fld->name;
            $this->view->prev = $this->mdl()->prevSectionsGet($fld, $current_fld->filename, $current_fld->parent);
        }
        $this->view->prevmain = 'abiturient';
        if($fld == 'abiturient') $this->view->first = true;
        $this->view->render('main/docs');
    }
}